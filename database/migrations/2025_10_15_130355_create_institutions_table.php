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
        Schema::create('institutions', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('country_id')->constrained()->cascadeOnDelete(); // MUST belong to a country
            $table->string('name');
            $table->string('logo')->nullable();
            $table->text('description')->nullable();
            $table->string('institution_type')->nullable(); // University, College, etc.
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->text('address')->nullable();
            $table->string('website')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->json('rankings')->nullable(); // QS, THE, etc.
            $table->text('accreditation')->nullable();
            $table->json('facilities')->nullable(); // Library, Labs, Accommodation, etc.
            $table->text('campus_life')->nullable();
            $table->integer('established_year')->nullable();
            $table->decimal('commission_rate', 5, 2)->nullable(); // % commission
            $table->string('commission_type')->nullable(); // percentage, fixed
            $table->json('contact_persons')->nullable(); // Array of contact details
            $table->boolean('is_partner')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->index('country_id');
            $table->index('is_partner');
            $table->index('is_active');
            $table->index('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('institutions');
    }
};
