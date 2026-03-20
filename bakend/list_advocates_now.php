<?php
require __DIR__.'/vendor/autoload.php';
$app = require __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

foreach (App\Models\Advocate::all() as $a) {
    echo $a->id . ' | ' . $a->enrollment_no . '/' . $a->year . ' | ' . ($a->name ?? 'NULL') . PHP_EOL;
}
echo 'Total: ' . App\Models\Advocate::count() . PHP_EOL;
