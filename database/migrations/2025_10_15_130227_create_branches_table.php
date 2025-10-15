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
        Schema::create('branches', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('name');
            $table->string('code')->unique(); // Branch identifier code
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('currency', 3)->default('USD'); // ISO 4217 currency code
            $table->string('timezone')->default('UTC');
            $table->json('working_hours')->nullable(); // Store working hours for each day
            $table->json('representing_countries')->nullable(); // Array of country IDs they represent
            $table->json('territories')->nullable(); // Regions/territories this branch manages
            $table->boolean('is_active')->default(true);
            $table->boolean('is_main')->default(false); // Main/Head office flag
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
        Schema::dropIfExists('branches');
    }
};
