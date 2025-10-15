<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;

final class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create pending tasks
        \App\Models\Task::factory()->pending()->count(15)->create();

        // Create urgent tasks
        \App\Models\Task::factory()->urgent()->count(5)->create();

        // Create completed tasks
        \App\Models\Task::factory()->completed()->count(10)->create();

        // Create other tasks
        \App\Models\Task::factory()->count(20)->create();
    }
}
