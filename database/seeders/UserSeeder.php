<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Database\Seeder;

final class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->createPlatformUsers();
        $this->createTenantUsers();

        $this->command->info('✅ Users seeded with roles successfully!');
    }

    /**
     * Create platform-level users (SuperAdmin, Support).
     */
    private function createPlatformUsers(): void
    {
        // Set team context to null for platform users
        setPermissionsTeamId(null);

        // Create SuperAdmin user
        $superAdmin = User::firstOrCreate(
            ['email' => 'superadmin@platform.com'],
            [
                'organization_id' => null, // Platform users have no organization
                'name' => 'Super Admin',
                'password' => 'password',
                'email_verified_at' => now(),
            ]
        );
        $superAdmin->assignRole('SuperAdmin');

        // Create Support user
        $support = User::firstOrCreate(
            ['email' => 'support@platform.com'],
            [
                'organization_id' => null, // Platform users have no organization
                'name' => 'Support Staff',
                'password' => 'password',
                'email_verified_at' => now(),
            ]
        );
        $support->assignRole('Support');

        $this->command->info('✅ Platform users created: SuperAdmin, Support');
    }

    /**
     * Create tenant-level users for each organization.
     */
    private function createTenantUsers(): void
    {
        $organizations = Organization::all();

        foreach ($organizations as $organization) {
            // Set team context to the organization
            setPermissionsTeamId($organization->id);

            // Create Admin user
            $admin = User::firstOrCreate(
                ['email' => "admin@{$organization->slug}.com"],
                [
                    'organization_id' => $organization->id,
                    'name' => "{$organization->name} - Admin",
                    'password' => 'password',
                    'email_verified_at' => now(),
                ]
            );
            $admin->assignRole('Admin');

            // Create Branch Manager user
            $branchManager = User::firstOrCreate(
                ['email' => "branch@{$organization->slug}.com"],
                [
                    'organization_id' => $organization->id,
                    'name' => "{$organization->name} - Branch Manager",
                    'password' => 'password',
                    'email_verified_at' => now(),
                ]
            );
            $branchManager->assignRole('BranchManager');

            // Create Counsellor user
            $counsellor = User::firstOrCreate(
                ['email' => "counsellor@{$organization->slug}.com"],
                [
                    'organization_id' => $organization->id,
                    'name' => "{$organization->name} - Counsellor",
                    'password' => 'password',
                    'email_verified_at' => now(),
                ]
            );
            $counsellor->assignRole('Counsellor');

            // Create Processing Officer user
            $processingOfficer = User::firstOrCreate(
                ['email' => "processing@{$organization->slug}.com"],
                [
                    'organization_id' => $organization->id,
                    'name' => "{$organization->name} - Processing Officer",
                    'password' => 'password',
                    'email_verified_at' => now(),
                ]
            );
            $processingOfficer->assignRole('ProcessingOfficer');

            // Create Front Office user
            $frontOffice = User::firstOrCreate(
                ['email' => "frontoffice@{$organization->slug}.com"],
                [
                    'organization_id' => $organization->id,
                    'name' => "{$organization->name} - Front Office",
                    'password' => 'password',
                    'email_verified_at' => now(),
                ]
            );
            $frontOffice->assignRole('FrontOffice');

            // Create Finance user
            $finance = User::firstOrCreate(
                ['email' => "finance@{$organization->slug}.com"],
                [
                    'organization_id' => $organization->id,
                    'name' => "{$organization->name} - Finance",
                    'password' => 'password',
                    'email_verified_at' => now(),
                ]
            );
            $finance->assignRole('Finance');

            $this->command->info("✅ Created 6 tenant users for: {$organization->name}");
        }
    }
}
