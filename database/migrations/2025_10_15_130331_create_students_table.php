<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('lead_id')->nullable()->constrained()->nullOnDelete(); // Original lead
            $table->foreignUlid('branch_id')->constrained()->cascadeOnDelete();
            $table->foreignUlid('assigned_counsellor')->nullable()->constrained('users')->nullOnDelete();
            $table->string('student_id')->unique(); // Auto-generated student ID
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->string('alternate_phone')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('gender')->nullable();
            $table->string('nationality');
            $table->string('passport_number')->nullable();
            $table->date('passport_expiry')->nullable();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_phone')->nullable();
            $table->string('emergency_contact_relationship')->nullable();
            $table->string('highest_education_level')->nullable();
            $table->string('field_of_study')->nullable();
            $table->decimal('gpa', 4, 2)->nullable();
            $table->json('academic_history')->nullable(); // Array of education records
            $table->json('work_experience')->nullable(); // Array of work records
            $table->json('english_proficiency')->nullable(); // IELTS, TOEFL, etc. scores
            $table->json('other_tests')->nullable(); // GRE, GMAT, SAT, etc.
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('student_id');
            $table->index('email');
            $table->index('branch_id');
            $table->index('assigned_counsellor');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
