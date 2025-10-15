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
        Schema::create('countries', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('name');
            $table->string('code', 3)->unique(); // ISO 3166-1 alpha-3 code
            $table->string('region')->nullable(); // e.g., Europe, Asia, North America
            $table->string('flag')->nullable(); // URL or path to flag image
            $table->text('application_process_info')->nullable();
            $table->json('visa_types')->nullable(); // Country-specific visa types
            $table->json('required_documents')->nullable(); // Common required documents
            $table->json('application_stages')->nullable(); // Country-specific application stages
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('code');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('countries');
    }
};
