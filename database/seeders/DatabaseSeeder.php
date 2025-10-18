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
        // Step 1: Create organizations first (multi-tenant foundation)
        $this->call(OrganizationSeeder::class);

        // Step 2: Get first organization for demo admin user
        $firstOrg = Organization::first();

        // Step 3: Create admin user for the first organization
        if ($firstOrg) {
            User::firstOrCreate(
                ['email' => 'admin@example.com'],
                [
                    'organization_id' => $firstOrg->id,
                    'name' => 'Admin User',
                    'password' => 'password',
                    'email_verified_at' => now(),
                ]
            );
        }

        // Step 4: Seed global data (not tenant-specific)
        $this->call([
            ApplicationProcessSeeder::class,      // Global application status templates
        ]);

        // Step 5: Seed tenant-scoped data for each organization
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
    }
}
