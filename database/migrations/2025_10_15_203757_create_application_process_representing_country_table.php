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
        Schema::create('application_process_representing_country', function (Blueprint $table) {
            $table->foreignUlid('application_process_id')
                ->constrained('application_processes')
                ->cascadeOnDelete()
                ->name('ap_rc_process_fk');
            $table->foreignUlid('representing_country_id')
                ->constrained('representing_countries')
                ->cascadeOnDelete()
                ->name('ap_rc_country_fk');
            $table->timestamps();

            $table->primary(['application_process_id', 'representing_country_id'], 'ap_rc_primary');
            $table->index('representing_country_id', 'ap_rc_country_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('application_process_representing_country');
    }
};
