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
        Schema::create('representing_countries', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('country_id')->constrained()->cascadeOnDelete();
            $table->decimal('monthly_living_cost', 10, 2)->nullable(); // Average monthly living cost
            $table->text('visa_requirements')->nullable(); // Visa requirements and details
            $table->text('part_time_work_details')->nullable(); // Part-time work regulations and details
            $table->text('country_benefits')->nullable(); // Benefits of studying in this country
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->index('country_id');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('representing_countries');
    }
};
