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
        // Note: Spatie Permission already creates unique constraint [organization_id, name, guard_name] for roles
        // when teams are enabled, so we don't need to modify roles table

        // For permissions, add organization_id to unique constraint
        Schema::table('permissions', function (Blueprint $table) {
            // Drop the old unique constraint (name + guard_name)
            $table->dropUnique(['name', 'guard_name']);

            // Add new unique constraint (name + guard_name + organization_id)
            // This allows same permission names across different organizations
            $table->unique(['name', 'guard_name', 'organization_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('permissions', function (Blueprint $table) {
            $table->dropUnique(['name', 'guard_name', 'organization_id']);
            $table->unique(['name', 'guard_name']);
        });
    }
};
