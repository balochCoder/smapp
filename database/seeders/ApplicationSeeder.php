<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;

final class ApplicationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create draft applications
        \App\Models\Application::factory()->draft()->count(5)->create();

        // Create submitted applications
        \App\Models\Application::factory()->submitted()->count(10)->create();

        // Create applications with offers
        \App\Models\Application::factory()->offered()->count(8)->create();

        // Create applications with various statuses
        \App\Models\Application::factory()->count(20)->create();
    }
}
