<?php

declare(strict_types=1);

namespace Database\Seeders;

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
        // Create admin user
        User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => 'password',
                'email_verified_at' => now(),
            ]
        );

        // Seed all tables in order (respecting foreign key relationships)
        $this->call([
            RepresentingCountrySeeder::class,     // Needs countries
            ApplicationProcessSeeder::class,      // Needs representing countries
            BranchSeeder::class,                  // Needs users (auto-created by factories)
            LeadSeeder::class,                    // Needs branches, users
            StudentSeeder::class,                 // Needs branches, users, optionally leads
            InstitutionSeeder::class,             // Needs countries
            CourseSeeder::class,                  // Needs institutions
            ApplicationSeeder::class,             // Needs students, branches, countries, institutions, courses, users
            TaskSeeder::class,                    // Needs users, branches, optionally applications/leads/students
            FollowUpSeeder::class,                // Needs users, optionally leads/students/applications
        ]);
    }
}
