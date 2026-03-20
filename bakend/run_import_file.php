<?php
require __DIR__.'/vendor/autoload.php';
$app = require __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Maatwebsite\Excel\Facades\Excel;
use App\Imports\AdvocatesImport;

$path = $argv[1] ?? 'storage/app/private/imports/01KHJHZ57SDTKMK9D2QC93RR1W.xlsx';
$full = realpath($path) ?: $path;

echo "Importing: {$full}\n";

$before = \App\Models\Advocate::count();
try {
    Excel::import(new AdvocatesImport(), $full);
    echo "Import finished.\n";
} catch (\Throwable $e) {
    echo "Import failed: " . $e->getMessage() . "\n";
    exit(1);
}
$after = \App\Models\Advocate::count();

echo "DB count before: {$before}, after: {$after}\n";

// Count non-empty rows in file (all sheets)
try {
    $sheets = Excel::toArray([], $full);
    $rowCount = 0;
    foreach ($sheets as $sheet) {
        foreach ($sheet as $r) {
            $vals = array_map(function($v){ return trim((string)$v); }, $r);
            if (count(array_filter($vals)) > 0) {
                $rowCount++;
            }
        }
    }
    echo "Total non-empty rows in file (all sheets): {$rowCount}\n";
} catch (\Throwable $e) {
    echo "Could not count rows: " . $e->getMessage() . "\n";
}

$logFile = storage_path('logs/import_activity.log');
if (file_exists($logFile)) {
    echo "--- import_activity.log (last 200 lines) ---\n";
    $lines = explode("\n", trim(file_get_contents($logFile)));
    $tail = array_slice($lines, -200);
    foreach ($tail as $line) echo $line . "\n";
} else {
    echo "No import_activity.log found.\n";
}
