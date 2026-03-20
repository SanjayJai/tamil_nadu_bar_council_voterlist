<?php
require __DIR__.'/vendor/autoload.php';
$app = require __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Maatwebsite\Excel\Facades\Excel;

$path = $argv[1] ?? 'storage/app/private/imports/01KHJHZ57SDTKMK9D2QC93RR1W.xlsx';
$full = realpath($path) ?: $path;
if (!file_exists($full)) { echo "File not found: {$full}\n"; exit(1); }

$sheets = Excel::toArray([], $full);
$rows = [];
foreach ($sheets as $sheet) {
    // header detection
    $headerIndex = null;
    foreach ($sheet as $i => $r) {
        $filled = array_filter(array_map('trim', array_map('strval', $r)), function($v){return $v !== '';});
        if (count($filled) > 0) { $headerIndex = $i; break; }
    }
    if ($headerIndex === null) continue;
    $header = $sheet[$headerIndex];
    for ($i = $headerIndex + 1; $i < count($sheet); $i++) {
        $r = $sheet[$i];
        $vals = array_map(function($v){ return trim((string)$v); }, $r);
        if (count(array_filter($vals)) === 0) continue;
        $assoc = [];
        foreach ($header as $c => $h) {
            $key = strtolower(trim(preg_replace('/[^a-z0-9]+/i', '_', (string)$h)));
            $assoc[$key] = $vals[$c] ?? '';
        }
        $en = $assoc['enrollment_number'] ?? $assoc['enrolment_number'] ?? $assoc['enrollment_no'] ?? $assoc['enrolment_no'] ?? $assoc['e_no'] ?? $assoc['enrollment'] ?? '';
        if ($en === '') $en = $vals[0] ?? '';
        // parse number and year: left/right of slash
        $en_str = trim($en);
        $en_no = null; $year = null;
        if (preg_match('/(\d+)\s*\/\s*(\d{2,4})/', $en_str, $m)) { $en_no = (int)$m[1]; $year = (string)$m[2]; }
        else if (preg_match('/(\d{3,})/', $en_str, $m2)) { $en_no = (int)$m2[1]; }
        else { $digits = preg_replace('/[^0-9]/','',$en_str); if ($digits !== '') $en_no = (int)$digits; }
        $rows[] = ['enrollment_str' => $en_str, 'enrollment_no' => $en_no, 'year' => $year, 'name' => $assoc['name'] ?? ''];
    }
}

$missing = [];
foreach ($rows as $r) {
    if ($r['enrollment_no'] === null || $r['year'] === null) { $missing[] = ['row' => $r, 'reason' => 'no_enrollment_or_year']; continue; }
    $found = \App\Models\Advocate::where('enrollment_no', $r['enrollment_no'])->where('year', $r['year'])->exists();
    if (! $found) $missing[] = ['row' => $r, 'reason' => 'not_in_db'];
}

echo "Total parsed rows: " . count($rows) . PHP_EOL;
echo "Missing vs DB count: " . count($missing) . PHP_EOL;
foreach ($missing as $m) echo json_encode($m, JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE) . PHP_EOL;
