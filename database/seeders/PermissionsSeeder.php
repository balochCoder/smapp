<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

final class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Platform-level permissions (for managing the SaaS platform itself)
        $platformPermissions = [
            'manage-organizations',
            'view-all-organizations',
            'suspend-organizations',
            'manage-platform-settings',
            'view-platform-analytics',
            'manage-platform-users',
            'view-system-logs',
        ];

        foreach ($platformPermissions as $permission) {
            Permission::create([
                'name' => $permission,
                'guard_name' => 'web',
                'organization_id' => null, // Platform permissions are global
            ]);
        }

        // Tenant-level permissions (for managing within an organization)
        // These will be created without organization_id here (global permissions)
        // They will be scoped to organizations when assigned to roles/users

        // Lead Management
        $leadPermissions = [
            'create-leads',
            'view-leads',
            'edit-leads',
            'delete-leads',
            'export-leads',
            'assign-leads',
        ];

        // Student Management
        $studentPermissions = [
            'create-students',
            'view-students',
            'edit-students',
            'delete-students',
            'export-students',
        ];

        // Application Management
        $applicationPermissions = [
            'create-applications',
            'view-applications',
            'edit-applications',
            'delete-applications',
            'submit-applications',
            'process-applications',
            'track-applications',
            'export-applications',
        ];

        // Institution Management
        $institutionPermissions = [
            'create-institutions',
            'view-institutions',
            'edit-institutions',
            'delete-institutions',
        ];

        // Course Management
        $coursePermissions = [
            'create-courses',
            'view-courses',
            'edit-courses',
            'delete-courses',
            'search-courses',
        ];

        // Branch Management
        $branchPermissions = [
            'create-branches',
            'view-branches',
            'edit-branches',
            'delete-branches',
            'manage-branch-users',
        ];

        // User Management
        $userPermissions = [
            'create-users',
            'view-users',
            'edit-users',
            'delete-users',
            'assign-roles',
            'manage-permissions',
        ];

        // Reporting
        $reportPermissions = [
            'view-reports',
            'export-reports',
            'view-analytics',
            'view-dashboard',
        ];

        // Financial
        $financialPermissions = [
            'view-payments',
            'manage-payments',
            'view-invoices',
            'manage-invoices',
            'view-commissions',
        ];

        // Representing Countries
        $representingCountryPermissions = [
            'create-representing-countries',
            'view-representing-countries',
            'edit-representing-countries',
            'delete-representing-countries',
            'manage-country-status',
        ];

        // Tasks & Follow-ups
        $taskPermissions = [
            'create-tasks',
            'view-tasks',
            'edit-tasks',
            'delete-tasks',
            'assign-tasks',
            'create-follow-ups',
            'view-follow-ups',
            'edit-follow-ups',
            'delete-follow-ups',
        ];

        // Settings
        $settingsPermissions = [
            'view-settings',
            'edit-settings',
            'manage-organization',
        ];

        // Merge all tenant permissions
        $tenantPermissions = array_merge(
            $leadPermissions,
            $studentPermissions,
            $applicationPermissions,
            $institutionPermissions,
            $coursePermissions,
            $branchPermissions,
            $userPermissions,
            $reportPermissions,
            $financialPermissions,
            $representingCountryPermissions,
            $taskPermissions,
            $settingsPermissions
        );

        foreach ($tenantPermissions as $permission) {
            Permission::create([
                'name' => $permission,
                'guard_name' => 'web',
                'organization_id' => null, // These are global permissions, scoped by team on assignment
            ]);
        }

        $this->command->info('Permissions created successfully!');
    }
}
