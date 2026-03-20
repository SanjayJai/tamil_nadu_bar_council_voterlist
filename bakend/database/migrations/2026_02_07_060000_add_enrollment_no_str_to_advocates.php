<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (! Schema::hasColumn('advocates', 'enrollment_no_str')) {
            Schema::table('advocates', function (Blueprint $table) {
                $table->string('enrollment_no_str')->nullable()->after('enrollment_no');
            });
        }

        // Copy existing numeric enrollment_no into the new string column
        try {
            \Illuminate\Support\Facades\DB::table('advocates')
                ->whereNotNull('enrollment_no')
                ->update(['enrollment_no_str' => \Illuminate\Support\Facades\DB::raw("CAST(enrollment_no AS TEXT)")]);
        } catch (\Throwable $e) {
            // ignore on sqlite or environments where CAST may differ
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('advocates', 'enrollment_no_str')) {
            Schema::table('advocates', function (Blueprint $table) {
                $table->dropColumn('enrollment_no_str');
            });
        }
    }
};
