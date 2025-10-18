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
        // Add is_platform_role to roles table
        // Note: organization_id is already added by Spatie Permission when teams are enabled
        Schema::table('roles', function (Blueprint $table) {
            // Platform-level roles flag (SuperAdmin, Support, etc.)
            $table->boolean('is_platform_role')->default(false)->after('guard_name');

            // Index for efficient queries
            $table->index(['organization_id', 'is_platform_role']);
        });

        // Add organization_id to permissions table (Spatie doesn't add this by default)
        Schema::table('permissions', function (Blueprint $table) {
            $table->foreignUlid('organization_id')->nullable()->after('guard_name')->constrained()->cascadeOnDelete();
            $table->index('organization_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('permissions', function (Blueprint $table) {
            $table->dropForeign(['organization_id']);
            $table->dropIndex(['organization_id']);
            $table->dropColumn('organization_id');
        });

        Schema::table('roles', function (Blueprint $table) {
            $table->dropIndex(['organization_id', 'is_platform_role']);
            $table->dropColumn('is_platform_role');
        });
    }
};
