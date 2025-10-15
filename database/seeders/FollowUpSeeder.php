<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;

final class FollowUpSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create follow-ups for leads
        \App\Models\FollowUp::factory()->forLead()->count(10)->create();

        // Create follow-ups for applications
        \App\Models\FollowUp::factory()->forApplication()->count(8)->create();

        // Create pending follow-ups
        \App\Models\FollowUp::factory()->pending()->count(12)->create();

        // Create completed follow-ups
        \App\Models\FollowUp::factory()->completed()->count(10)->create();

        // Create overdue follow-ups
        \App\Models\FollowUp::factory()->overdue()->count(5)->create();
    }
}
