<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rep_country_status', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('sub_statuses', function (Blueprint $table) {
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::table('rep_country_status', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('sub_statuses', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
