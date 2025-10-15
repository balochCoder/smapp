<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;

final class LeadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create some new leads
        \App\Models\Lead::factory()->newLead()->count(10)->create();

        // Create qualified leads
        \App\Models\Lead::factory()->qualified()->count(5)->create();

        // Create some lost leads
        \App\Models\Lead::factory()->lost()->count(3)->create();

        // Create other leads with various statuses
        \App\Models\Lead::factory()->count(12)->create();
    }
}
