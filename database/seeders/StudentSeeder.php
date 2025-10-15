<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;

final class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create students converted from leads
        \App\Models\Student::factory()->fromLead()->count(5)->create();

        // Create students without lead association
        \App\Models\Student::factory()->count(15)->create();
    }
}
