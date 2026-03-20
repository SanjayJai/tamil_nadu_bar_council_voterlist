<?php
require __DIR__.'/vendor/autoload.php';
$app = require __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Advocate;

$rows = Advocate::all()->map(function($a){
    return [
        'id' => $a->id,
        'enrollment_no' => $a->enrollment_no,
        'enrollment_no_str' => $a->enrollment_no_str,
        'year' => $a->year,
        'name' => $a->name,
        'membership_details' => $a->membership_details,
        'address' => $a->address,
        'bar_association' => $a->bar_association,
    ];
})->toArray();

$missing = array_filter($rows, function($r){
    return empty($r['name']) || empty($r['address']) || empty($r['membership_details']) || empty($r['bar_association']);
});

if (empty($missing)) {
    echo "No missing key fields detected.\n";
    exit(0);
}

foreach ($missing as $r) {
    echo json_encode($r, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES) . PHP_EOL;
}
