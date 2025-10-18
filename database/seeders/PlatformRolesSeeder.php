<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

final class PlatformRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Creates platform-level roles for managing the SaaS platform itself.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Create SuperAdmin role (platform level)
        $superAdmin = Role::create([
            'name' => 'SuperAdmin',
            'guard_name' => 'web',
        ]);

        // Manually set is_platform_role and organization_id
        DB::table('roles')
            ->where('id', $superAdmin->id)
            ->update([
                'is_platform_role' => true,
                'organization_id' => null,
            ]);

        // Give all platform permissions to SuperAdmin
        $platformPermissions = Permission::whereIn('name', [
            'manage-organizations',
            'view-all-organizations',
            'suspend-organizations',
            'manage-platform-settings',
            'view-platform-analytics',
            'manage-platform-users',
            'view-system-logs',
        ])->get();

        $superAdmin->syncPermissions($platformPermissions);

        // Create Support role (platform level) - limited access
        $support = Role::create([
            'name' => 'Support',
            'guard_name' => 'web',
        ]);

        DB::table('roles')
            ->where('id', $support->id)
            ->update([
                'is_platform_role' => true,
                'organization_id' => null,
            ]);

        // Give limited platform permissions to Support
        $supportPermissions = Permission::whereIn('name', [
            'view-all-organizations',
            'view-platform-analytics',
            'view-system-logs',
        ])->get();

        $support->syncPermissions($supportPermissions);

        $this->command->info('Platform roles created successfully!');
        $this->command->info('- SuperAdmin (full platform access)');
        $this->command->info('- Support (limited platform access)');
    }
}
