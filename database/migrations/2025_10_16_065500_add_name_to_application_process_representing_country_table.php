<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('application_process_representing_country', function (Blueprint $table) {
            $table->string('custom_name')->nullable()->after('is_active');
        });
    }

    public function down(): void
    {
        Schema::table('application_process_representing_country', function (Blueprint $table) {
            $table->dropColumn('custom_name');
        });
    }
};
