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
        Schema::create('applications', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('application_number')->unique(); // Auto-generated unique ID
            $table->foreignUlid('student_id')->constrained()->cascadeOnDelete();
            $table->foreignUlid('branch_id')->constrained()->cascadeOnDelete();
            $table->foreignUlid('country_id')->constrained()->cascadeOnDelete(); // Representing country
            $table->foreignUlid('institution_id')->constrained()->cascadeOnDelete();
            $table->foreignUlid('course_id')->constrained()->cascadeOnDelete();
            $table->foreignUlid('assigned_officer')->nullable()->constrained('users')->nullOnDelete(); // Processing officer
            $table->string('intake')->nullable(); // Fall 2024, Spring 2025
            $table->date('intake_date')->nullable();
            $table->string('status')->default('draft'); // draft, submitted, under-review, offer, conditional-offer, rejected, accepted, visa-applied, visa-granted, enrolled
            $table->string('current_stage')->nullable(); // Country-specific stage
            $table->json('workflow_stages')->nullable(); // Track progress through country-specific workflow
            $table->date('application_date')->nullable();
            $table->date('decision_date')->nullable();
            $table->text('decision_notes')->nullable();
            $table->json('document_checklist')->nullable(); // Required documents with status
            $table->boolean('conditional_offer')->default(false);
            $table->json('offer_conditions')->nullable();
            $table->text('offer_letter_path')->nullable();
            $table->date('offer_expiry_date')->nullable();
            $table->decimal('application_fee', 10, 2)->nullable();
            $table->boolean('application_fee_paid')->default(false);
            $table->decimal('tuition_deposit', 10, 2)->nullable();
            $table->boolean('tuition_deposit_paid')->default(false);
            $table->string('visa_status')->nullable(); // applied, approved, rejected, not-required
            $table->date('visa_application_date')->nullable();
            $table->date('visa_decision_date')->nullable();
            $table->text('notes')->nullable();
            $table->text('internal_notes')->nullable(); // Not visible to student
            $table->timestamps();
            $table->softDeletes();

            $table->index('application_number');
            $table->index('student_id');
            $table->index('branch_id');
            $table->index('country_id');
            $table->index('institution_id');
            $table->index('course_id');
            $table->index('status');
            $table->index('current_stage');
            $table->index('assigned_officer');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
