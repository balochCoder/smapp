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
        Schema::create('follow_ups', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('lead_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignUlid('student_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignUlid('application_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignUlid('created_by')->constrained('users')->cascadeOnDelete();
            $table->foreignUlid('assigned_to')->constrained('users')->cascadeOnDelete();
            $table->string('type')->nullable(); // call, email, meeting, whatsapp
            $table->string('subject')->nullable();
            $table->text('notes')->nullable();
            $table->date('follow_up_date');
            $table->time('follow_up_time')->nullable();
            $table->string('status')->default('pending'); // pending, completed, overdue, cancelled
            $table->text('outcome')->nullable(); // Result of the follow-up
            $table->timestamp('completed_at')->nullable();
            $table->foreignUlid('completed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->boolean('reminder_sent')->default(false);
            $table->timestamp('reminder_sent_at')->nullable();
            $table->timestamps();

            $table->index('lead_id');
            $table->index('student_id');
            $table->index('application_id');
            $table->index('assigned_to');
            $table->index('status');
            $table->index('follow_up_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('follow_ups');
    }
};
