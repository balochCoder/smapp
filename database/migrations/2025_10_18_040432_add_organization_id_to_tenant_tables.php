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
        // Add organization_id to users (nullable for platform users like SuperAdmin)
        Schema::table('users', function (Blueprint $table) {
            $table->foreignUlid('organization_id')->nullable()->after('id')->constrained()->cascadeOnDelete();
            $table->index('organization_id');
        });

        // Tenant-scoped tables that need organization_id (not nullable)
        $tables = [
            'branches',
            'representing_countries',
            'institutions',
            'courses',
            'students',
            'leads',
            'applications',
            'tasks',
            'follow_ups',
        ];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->foreignUlid('organization_id')->after('id')->constrained()->cascadeOnDelete();
                $table->index('organization_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = [
            'users',
            'branches',
            'representing_countries',
            'institutions',
            'courses',
            'students',
            'leads',
            'applications',
            'tasks',
            'follow_ups',
        ];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->dropForeign(['organization_id']);
                $table->dropIndex(['organization_id']);
                $table->dropColumn('organization_id');
            });
        }
    }
};
