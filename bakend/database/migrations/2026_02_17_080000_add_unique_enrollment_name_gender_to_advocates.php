<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Adds a composite unique index on (enrollment_no, name, gender) so imports
     * will update existing records when those three fields match.
     */
    public function up(): void
    {
        Schema::table('advocates', function (Blueprint $table) {
            try {
                $table->unique(['enrollment_no', 'name', 'gender'], 'adv_enroll_name_gender_unique');
            } catch (\Throwable $e) {
                // If the index cannot be created (duplicates present or different DB naming), ignore.
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('advocates', function (Blueprint $table) {
            try {
                $table->dropUnique('adv_enroll_name_gender_unique');
            } catch (\Throwable $e) {
                // ignore
            }
        });
    }
};
