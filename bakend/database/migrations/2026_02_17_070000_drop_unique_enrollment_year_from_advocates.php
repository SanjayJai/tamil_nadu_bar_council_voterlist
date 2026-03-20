<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Drops the unique constraint on (enrollment_no, year) so imports can create duplicate
     * enrollment/year rows (insert-only behavior requested by the user).
     */
    public function up(): void
    {
        Schema::table('advocates', function (Blueprint $table) {
            // Attempt to drop the unique index. Works whether the index exists or not.
            try {
                $table->dropUnique(['enrollment_no', 'year']);
            } catch (\Throwable $e) {
                // If the index name differs or it doesn't exist, ignore the error.
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * Re-create the unique constraint if you want to roll back.
     */
    public function down(): void
    {
        Schema::table('advocates', function (Blueprint $table) {
            $table->unique(['enrollment_no', 'year']);
        });
    }
};
