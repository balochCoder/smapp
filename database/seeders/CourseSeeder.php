<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;

final class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create featured courses
        \App\Models\Course::factory()->featured()->count(10)->create();

        // Create bachelor courses
        \App\Models\Course::factory()->bachelor()->count(20)->create();

        // Create master courses
        \App\Models\Course::factory()->master()->count(15)->create();

        // Create other courses
        \App\Models\Course::factory()->count(25)->create();
    }
}
