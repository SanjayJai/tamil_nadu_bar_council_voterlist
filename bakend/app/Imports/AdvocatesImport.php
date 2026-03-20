<?php

namespace App\Imports;

use App\Models\Advocate;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;

class AdvocatesImport implements ToModel, WithHeadingRow, WithChunkReading, SkipsEmptyRows, WithCustomCsvSettings
{
    public function headingRow(): int
    {
        // Use first non-empty row as header in typical files; many exports have header at row 1.
        return 1;
    }

    public function model(array $row)
    {
        // incoming row provided by the reader
        // Try to get common headings first (accept British spelling 'enrolment')
        $enrollmentStr = trim((string) ($this->get($row, ['e_no', 'e_number', 'enrollment_no', 'enrolment_no', 'enrollment_number', 'enrolment_number', 'eno', 'e no', 'e number', 'enrolment', 'enrollment']) ?? ''));
        $name          = trim((string) ($this->get($row, ['name', 'advocate_name', 'advocate', 'full_name']) ?? ''));
        $year          = trim((string) ($this->get($row, ['year', 'enrol_year', 'enrollment_year']) ?? ''));

        // If required fields not found by header keys, try to heuristically detect from values
        if ($enrollmentStr === '' || $name === '' || $year === '') {
            $detected = $this->categorizeRow($row);
            $enrollmentStr = $enrollmentStr !== '' ? $enrollmentStr : ($detected['enrollment_str'] ?? '');
            $name = $name !== '' ? $name : ($detected['name'] ?? '');
            $year = $year !== '' ? $year : ($detected['year'] ?? '');
        }

        // If we still don't have an enrollment or year, try extracting year from the enrollment string.
        if ($year === '' && preg_match('/\/(\s*(19|20)\d{2}\b)/', $enrollmentStr, $m)) {
            $year = trim($m[1]);
        }

        if ($enrollmentStr === '') {
            return null;
        }

        // Parse enrollment string: prefer the left-side number before a slash (e.g. 1067/2011 -> enrollment 1067, year 2011)
        $enrollmentNo = null;
        if (preg_match('/(\d+)\s*\/\s*(\d{2,4})/', $enrollmentStr, $m)) {
            $enrollmentNo = $m[1];
            if ($year === '') {
                $year = $m[2];
            }
        } else {
            // fallback: first long numeric group
            if (preg_match('/(\d{3,})/', $enrollmentStr, $m2)) {
                $enrollmentNo = $m2[1];
            } else {
                // final fallback: strip non-digits (may concatenate year) and use leading digits
                $digits = preg_replace('/[^0-9]/', '', $enrollmentStr);
                $enrollmentNo = $digits !== '' ? $digits : null;
            }
        }

        if ($enrollmentNo === null || $year === '') {
            // still missing essential pieces -> skip
            return null;
        }

        // Require a name (database column is not nullable). If name missing, skip the row.
        if ($name === '') {
            return null;
        }

        $data = [
            'enrollment_no'     => (int) $enrollmentNo,
            'enrollment_no_str' => $enrollmentStr,
            'year'              => $year,
            'name'              => $name ?: null,
            // Default to 'OTHER' when gender can't be determined to satisfy DB enum/not-null
            'gender'            => $this->normalizeGender($this->get($row, ['gender']) ?? null) ?? 'OTHER',
            'father_name'       => trim((string) ($this->get($row, ['father_name']) ?? '')) ?: null,
            'bar_association'   => trim((string) ($this->get($row, ['bar_association', 'bar association', 'bar_association', 'bar_assoc', 'bar_assn']) ?? '')) ?: null,
            'district'          => trim((string) ($this->get($row, ['district']) ?? '')) ?: null,
            'membership_details' => trim((string) ($this->get($row, ['membership', 'membership_details', 'membership detail', 'membership details']) ?? '')) ?: null,
            'address'            => trim((string) ($this->get($row, ['address', 'addr', 'location']) ?? '')) ?: null,
        ];
        // Prefer update when enrollment_no + gender + normalized name match an existing record,
        // otherwise create a new record.
        $candidates = Advocate::where('enrollment_no', $enrollmentNo)
            ->where('gender', $data['gender'])
            ->get();

        foreach ($candidates as $existing) {
            if ($this->normalizeName($existing->name) === $this->normalizeName($name)) {
                $existing->update(array_filter($data));
                $this->logImportActivity('updated', $enrollmentNo, $year, $data, $row);
                return null;
            }
        }

        // No matching record — create a new one.
        $this->logImportActivity('created', $enrollmentNo, $year, $data, $row);
        return new Advocate(array_filter($data));
    }

    private function logImportActivity(string $action, $enrollmentNo, $year, array $data, array $row): void
    {
        try {
            $entry = [
                'time' => now()->toDateTimeString(),
                'action' => $action,
                'enrollment_no' => $enrollmentNo,
                'year' => $year,
                'data' => $data,
                'row' => $row,
            ];
            file_put_contents(storage_path('logs/import_activity.log'), json_encode($entry, JSON_UNESCAPED_UNICODE) . PHP_EOL, FILE_APPEND);
        } catch (\Throwable $e) {
            // ignore logging errors
        }
    }

    private function get(array $row, array $keys)
    {
        foreach ($keys as $key) {
            $key = strtolower(str_replace(' ', '_', $key));
            if (isset($row[$key]) && trim((string) $row[$key]) !== '') {
                return $row[$key];
            }
        }
        return null;
    }

    /**
     * Attempt to detect common fields when headings are missing or out of order.
     * Returns an associative array of detected values (enrollment_str, name, year, address, membership, gender, father_name, district)
     */
    private function categorizeRow(array $row): array
    {
        $out = [];
        // Normalize values: keep original keys and numeric indices
        $values = [];
        foreach ($row as $k => $v) {
            $s = trim((string) $v);
            if ($s === '') {
                continue;
            }
            $values[] = $s;
        }

        foreach ($values as $val) {
            $upper = strtoupper($val);

            // Enrollment patterns: look for digits with optional slash (e.g. 1067/2011) or 'E NO' prefixes
            if (! isset($out['enrollment_str']) && preg_match('/\d+\/\d+/', $val)) {
                $out['enrollment_str'] = $val;
                continue;
            }

            // Short numeric enrollment like '2048' or '1264' (often not unique alone) - accept if value is mostly digits and length >=3
            if (! isset($out['enrollment_str']) && preg_match('/^\s*\d{3,}\s*$/', $val)) {
                $out['enrollment_str'] = $val;
                continue;
            }

            // Year detection: 4-digit year or pattern like '/2011' in other strings
            if (! isset($out['year']) && preg_match('/\b(19|20)\d{2}\b/', $val, $m)) {
                $out['year'] = $m[0];
                continue;
            }

            // Membership detection
            if (! isset($out['membership']) && preg_match('/\b(MEMBER|NON\s*MEMBER|MEMBERSHIP)\b/i', $val)) {
                $out['membership'] = $val;
                continue;
            }

            // Address heuristics: contains common address tokens or comma-separated parts and state names
            if (! isset($out['address']) && (str_contains($upper, 'STREET') || str_contains($upper, 'ROAD') || str_contains($upper, 'NORTH') || str_contains($upper, 'SOUTH') || str_contains($upper, 'TAMIL NADU') || str_contains($upper, 'INDIA') || substr_count($val, ',') >= 1)) {
                $out['address'] = $val;
                continue;
            }

            // Gender detection
            if (! isset($out['gender']) && preg_match('/\b(MALE|FEMALE|M|F)\b/i', $val, $m)) {
                $g = strtoupper($m[0]);
                $out['gender'] = ($g === 'M' ? 'MALE' : ($g === 'F' ? 'FEMALE' : $g));
                continue;
            }

            // Name detection: assume any long alpha string with 2-4 words that is not address or membership
            if (! isset($out['name'])) {
                $words = preg_split('/\s+/', $val);
                $alphaCount = preg_match_all('/[A-Za-z]/', $val);
                if ($alphaCount > 2 && count($words) <= 6 && count($words) >= 2 && ! preg_match('/\d/', $val)) {
                    $out['name'] = $val;
                    continue;
                }
            }

            // Fallback: father_name or district if patterns match
            if (! isset($out['father_name']) && preg_match('/\b(ANNANAGAR|ANNA NAGAR|FATHER|S\/O|SON OF)\b/i', $val)) {
                $out['father_name'] = $val;
                continue;
            }
        }

        return $out;
    }

    private function normalizeGender(?string $gender): ?string
    {
        return match (strtoupper(trim((string) $gender))) {
            'MALE', 'M' => 'MALE',
            'FEMALE', 'F' => 'FEMALE',
            default => null,
        };
    }

    private function normalizeName(?string $name): string
    {
        $s = trim((string) $name);
        $s = preg_replace('/\s+/', ' ', $s);
        $s = strtolower($s);
        $s = str_replace(['.', ','], '', $s);
        return $s;
    }

    public function chunkSize(): int
    {
        return 1000;
    }

    public function getCsvSettings(): array
    {
        return [
            'delimiter' => ',',
            'enclosure' => '"',
            'escape' => '\\',
        ];
    }
}
