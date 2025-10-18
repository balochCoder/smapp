<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Organization;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

final class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Step 1: Seed RBAC permissions and roles (must be first)
        $this->call([
            PermissionsSeeder::class,           // Create all permissions
            PlatformRolesSeeder::class,         // Create platform roles (SuperAdmin, Support)
        ]);

        // Step 2: Create organizations first (multi-tenant foundation)
        $this->call(OrganizationSeeder::class);

        // Step 3: Create tenant roles for each organization
        $this->call(TenantRolesSeeder::class);

        // Step 4: Create users and assign roles
        $this->call(UserSeeder::class);

        // Step 5: Seed global data (not tenant-specific)
        $this->call([
            ApplicationProcessSeeder::class,      // Global application status templates
        ]);

        // Step 6: Seed tenant-scoped data for each organization
        // Note: The seeders will automatically scope data to the authenticated user's organization
        // due to the BelongsToOrganization trait and global scope
        $this->call([
            RepresentingCountrySeeder::class,     // Tenant-specific (needs countries)
            RepCountryStatusSeeder::class,        // Tenant-specific (needs representing countries and application processes)
            BranchSeeder::class,                  // Tenant-specific (needs organization_id)
            LeadSeeder::class,                    // Tenant-specific (needs branches, users)
            StudentSeeder::class,                 // Tenant-specific (needs branches, users, optionally leads)
            InstitutionSeeder::class,             // Tenant-specific (needs countries)
            CourseSeeder::class,                  // Tenant-specific (needs institutions)
            ApplicationSeeder::class,             // Tenant-specific (needs students, branches, countries, institutions, courses, users)
            TaskSeeder::class,                    // Tenant-specific (needs users, branches, optionally applications/leads/students)
            FollowUpSeeder::class,                // Tenant-specific (needs users, optionally leads/students/applications)
        ]);

        $this->command->info('✅ Multi-tenant database seeded successfully!');
        $this->command->info('📊 Organizations: '.Organization::count());
        $this->command->info('👥 Users: '.User::count());
        $this->command->info('🎭 Roles: '.\Spatie\Permission\Models\Role::count());
    }
}
