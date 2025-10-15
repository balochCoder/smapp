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
        Schema::create('courses', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('institution_id')->constrained()->cascadeOnDelete(); // MUST belong to institution
            $table->string('name');
            $table->string('code')->nullable(); // Course code
            $table->text('description')->nullable();
            $table->string('level'); // Bachelor, Master, PhD, Diploma, Certificate
            $table->string('subject_area')->nullable(); // Engineering, Business, Medicine, etc.
            $table->string('specialization')->nullable();
            $table->integer('duration_value')->nullable(); // e.g., 3
            $table->string('duration_unit')->nullable(); // years, months
            $table->decimal('tuition_fee', 12, 2)->nullable();
            $table->string('fee_currency', 3)->nullable(); // USD, GBP, AUD
            $table->string('fee_period')->nullable(); // per year, total
            $table->json('scholarships')->nullable(); // Available scholarships
            $table->json('intakes')->nullable(); // Fall, Spring, Summer with dates
            $table->text('entry_requirements')->nullable();
            $table->string('english_requirement')->nullable(); // IELTS 6.5, TOEFL 90
            $table->json('other_requirements')->nullable(); // GRE, GMAT, etc.
            $table->string('mode_of_study')->nullable(); // Full-time, Part-time, Online
            $table->text('career_outcomes')->nullable();
            $table->text('course_structure')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->timestamps();

            $table->index('institution_id');
            $table->index('level');
            $table->index('subject_area');
            $table->index('is_active');
            $table->index('is_featured');
            $table->fullText(['name', 'description']); // For full-text search
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
