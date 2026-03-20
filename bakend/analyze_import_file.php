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
foreach ($sheets as $sheetIndex => $sheet) {
    // find header row (first row with any non-empty cell)
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
        // extract common fields
        $en = $assoc['enrollment_number'] ?? $assoc['enrolment_number'] ?? $assoc['enrollment_no'] ?? $assoc['enrolment_no'] ?? $assoc['e_no'] ?? '';
        $name = $assoc['name'] ?? $assoc['advocate_name'] ?? $assoc['full_name'] ?? '';
        if ($en === '') {
            // try first column
            $en = $vals[0] ?? '';
        }
        $rows[] = ['sheet' => $sheetIndex, 'row_index' => $i+1, 'enrollment' => $en, 'name' => $name, 'raw' => $assoc];
    }
}

// read import_activity.log
$logFile = storage_path('logs/import_activity.log');
$log = file_exists($logFile) ? file_get_contents($logFile) : '';

$missing = [];
foreach ($rows as $r) {
    $en = trim($r['enrollment']);
    $name = trim($r['name']);
    $found = false;
    if ($en !== '') {
        if (strpos($log, $en) !== false) $found = true;
    }
    if (! $found && $name !== '') {
        if (strpos($log, $name) !== false) $found = true;
    }
    if (! $found) $missing[] = $r;
}

echo "Total data rows in file: " . count($rows) . PHP_EOL;
echo "Total log entries: " . (substr_count($log, PHP_EOL)) . PHP_EOL;
echo "Missing rows count: " . count($missing) . PHP_EOL;
foreach ($missing as $m) {
    echo json_encode($m, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES) . PHP_EOL;
}
