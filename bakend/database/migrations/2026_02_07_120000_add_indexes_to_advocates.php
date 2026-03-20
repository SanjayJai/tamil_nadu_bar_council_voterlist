<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('advocates', function (Blueprint $table) {
            $table->index('name', 'advocates_name_index');
            // If you store display form like "265(a)" keep string index
            if (! Schema::hasColumn('advocates', 'enrollment_no_str')) {
                // nothing
            } else {
                $table->index('enrollment_no_str', 'advocates_enrollment_no_str_index');
            }
            $table->index('enrollment_no', 'advocates_enrollment_no_index');
            // composite indexes for common lookups with year
            $table->index(['enrollment_no', 'year'], 'advocates_enrollment_no_year_idx');
            $table->index(['enrollment_no_str', 'year'], 'advocates_enrollment_no_str_year_idx');
        });
    }

    public function down(): void
    {
        Schema::table('advocates', function (Blueprint $table) {
            $table->dropIndex('advocates_name_index');
            if (Schema::hasColumn('advocates', 'enrollment_no_str')) {
                $table->dropIndex('advocates_enrollment_no_str_index');
            }
            $table->dropIndex('advocates_enrollment_no_index');
            $table->dropIndex('advocates_enrollment_no_year_idx');
            $table->dropIndex('advocates_enrollment_no_str_year_idx');
        });
    }
};
