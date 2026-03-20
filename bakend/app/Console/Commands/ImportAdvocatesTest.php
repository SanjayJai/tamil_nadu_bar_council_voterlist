<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\AdvocatesImport;

class ImportAdvocatesTest extends Command
{
    protected $signature = 'advocates:import-test {--file=storage/app/imports/test_sample.csv}';
    protected $description = 'Import a test CSV using AdvocatesImport';

    public function handle()
    {
        $file = base_path($this->option('file'));
        if (! file_exists($file)) {
            $this->error("File not found: {$file}");
            return 1;
        }

        try {
            Excel::import(new AdvocatesImport(), $file);
            $this->info('Import completed successfully.');
            return 0;
        } catch (\Throwable $e) {
            $this->error('Import failed: ' . $e->getMessage());
            return 1;
        }
    }
}
