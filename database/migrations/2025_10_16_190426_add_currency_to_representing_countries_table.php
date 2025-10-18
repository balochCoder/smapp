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
        Schema::table('representing_countries', function (Blueprint $table) {
            $table->string('currency', 3)->default('USD')->after('monthly_living_cost');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('representing_countries', function (Blueprint $table) {
            $table->dropColumn('currency');
        });
    }
};
