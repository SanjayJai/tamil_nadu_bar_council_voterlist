<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('advocates', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('enrollment_no');
            // Keep the original raw enrollment string (e.g. "1067/2011")
            $table->string('enrollment_no_str')->nullable();
            // Use a string for year to accept values like '2020(se)'
            $table->string('year')->nullable();
            $table->string('name');
            $table->enum('gender', ['MALE', 'FEMALE', 'OTHER']);
            $table->string('father_name')->nullable();
            $table->string('bar_association')->nullable();
            $table->string('district')->nullable();
            // Membership details from the list (e.g. "Member" / "Non Member")
            $table->string('membership_details')->nullable();
            // Full postal address from the import/CSV/PDF
            $table->text('address')->nullable();
            $table->timestamps();

            // Composite unique so same enrollment can exist across different years
            $table->unique(['enrollment_no', 'year'], 'advocates_enrollment_no_year_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('advocates');
    }
};
