<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Organization;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

final class TenantRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Creates tenant-level roles for each organization.
     * This should be called when a new organization is created.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Get all organizations
        $organizations = Organization::all();

        if ($organizations->isEmpty()) {
            $this->command->warn('No organizations found. Tenant roles will be created when organizations are registered.');

            return;
        }

        foreach ($organizations as $organization) {
            $this->createTenantRoles($organization);
        }

        $this->command->info('Tenant roles created successfully for all organizations!');
    }

    /**
     * Create tenant roles for a specific organization.
     */
    public function createTenantRoles(Organization $organization): void
    {
        // Set the current team context
        setPermissionsTeamId($organization->id);

        // 1. Admin - Full access within the organization
        $admin = Role::firstOrCreate(
            [
                'name' => 'Admin',
                'guard_name' => 'web',
                'organization_id' => $organization->id,
            ]
        );

        $adminPermissions = Permission::whereIn('name', [
            // All management permissions except platform-level
            'create-leads', 'view-leads', 'edit-leads', 'delete-leads', 'export-leads', 'assign-leads',
            'create-students', 'view-students', 'edit-students', 'delete-students', 'export-students',
            'create-applications', 'view-applications', 'edit-applications', 'delete-applications',
            'submit-applications', 'process-applications', 'track-applications', 'export-applications',
            'create-institutions', 'view-institutions', 'edit-institutions', 'delete-institutions',
            'create-courses', 'view-courses', 'edit-courses', 'delete-courses', 'search-courses',
            'create-branches', 'view-branches', 'edit-branches', 'delete-branches', 'manage-branch-users',
            'create-users', 'view-users', 'edit-users', 'delete-users', 'assign-roles', 'manage-permissions',
            'view-reports', 'export-reports', 'view-analytics', 'view-dashboard',
            'view-payments', 'manage-payments', 'view-invoices', 'manage-invoices', 'view-commissions',
            'create-representing-countries', 'view-representing-countries', 'edit-representing-countries',
            'delete-representing-countries', 'manage-country-status',
            'create-tasks', 'view-tasks', 'edit-tasks', 'delete-tasks', 'assign-tasks',
            'create-follow-ups', 'view-follow-ups', 'edit-follow-ups', 'delete-follow-ups',
            'view-settings', 'edit-settings', 'manage-organization',
        ])->get();
        $admin->syncPermissions($adminPermissions);

        // 2. Branch Manager - View-only access (can see everything but cannot add/edit/delete)
        $branchManager = Role::firstOrCreate(
            [
                'name' => 'BranchManager',
                'guard_name' => 'web',
                'organization_id' => $organization->id,
            ]
        );

        $branchManagerPermissions = Permission::whereIn('name', [
            'view-leads',
            'view-students',
            'view-applications', 'track-applications',
            'view-institutions', 'view-courses', 'search-courses',
            'view-branches',
            'view-users',
            'view-reports', 'view-analytics', 'view-dashboard',
            'view-representing-countries',
            'view-tasks',
            'view-follow-ups',
            'view-settings',
            'view-payments', 'view-invoices', 'view-commissions',
        ])->get();
        $branchManager->syncPermissions($branchManagerPermissions);

        // 3. Counsellor - Manage students and applications
        $counsellor = Role::firstOrCreate(
            [
                'name' => 'Counsellor',
                'guard_name' => 'web',
                'organization_id' => $organization->id,
            ]
        );

        $counsellorPermissions = Permission::whereIn('name', [
            'view-leads', 'edit-leads',
            'create-students', 'view-students', 'edit-students',
            'create-applications', 'view-applications', 'edit-applications', 'submit-applications', 'track-applications',
            'view-institutions', 'view-courses', 'search-courses',
            'view-dashboard',
            'view-representing-countries',
            'create-tasks', 'view-tasks', 'edit-tasks',
            'create-follow-ups', 'view-follow-ups', 'edit-follow-ups',
        ])->get();
        $counsellor->syncPermissions($counsellorPermissions);

        // 4. Processing Officer - Process applications
        $processingOfficer = Role::firstOrCreate(
            [
                'name' => 'ProcessingOfficer',
                'guard_name' => 'web',
                'organization_id' => $organization->id,
            ]
        );

        $processingOfficerPermissions = Permission::whereIn('name', [
            'view-students',
            'view-applications', 'edit-applications', 'process-applications', 'track-applications',
            'view-institutions', 'view-courses',
            'view-dashboard',
            'view-representing-countries',
            'view-tasks', 'edit-tasks',
            'view-follow-ups', 'edit-follow-ups',
        ])->get();
        $processingOfficer->syncPermissions($processingOfficerPermissions);

        // 5. Front Office - Lead capture and initial contact
        $frontOffice = Role::firstOrCreate(
            [
                'name' => 'FrontOffice',
                'guard_name' => 'web',
                'organization_id' => $organization->id,
            ]
        );

        $frontOfficePermissions = Permission::whereIn('name', [
            'create-leads', 'view-leads', 'edit-leads',
            'view-students',
            'view-courses', 'search-courses',
            'view-dashboard',
            'view-tasks',
            'create-follow-ups', 'view-follow-ups',
        ])->get();
        $frontOffice->syncPermissions($frontOfficePermissions);

        // 6. Finance - Financial tracking and invoicing
        $finance = Role::firstOrCreate(
            [
                'name' => 'Finance',
                'guard_name' => 'web',
                'organization_id' => $organization->id,
            ]
        );

        $financePermissions = Permission::whereIn('name', [
            'view-students',
            'view-applications',
            'view-reports', 'export-reports', 'view-dashboard',
            'view-payments', 'manage-payments', 'view-invoices', 'manage-invoices', 'view-commissions',
        ])->get();
        $finance->syncPermissions($financePermissions);

        if ($this->command) {
            $this->command->info("Created tenant roles for organization: {$organization->name}");
        }
    }
}
