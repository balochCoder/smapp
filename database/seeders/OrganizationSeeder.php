<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Organization;
use Illuminate\Database\Seeder;

final class OrganizationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 3 demo organizations for testing multi-tenancy
        Organization::factory()->count(3)->create();

        // Optionally create a specific demo organization
        Organization::factory()->create([
            'name' => 'Global Education Consultancy',
            'slug' => 'global-education',
            'email' => 'info@globaledu.com',
            'subscription_plan' => 'premium',
            'is_active' => true,
        ]);
    }
}
