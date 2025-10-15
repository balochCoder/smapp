<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Lead>
 */
final class LeadFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'branch_id' => \App\Models\Branch::inRandomOrder()->first()?->id ?? \App\Models\Branch::factory(),
            'assigned_to' => \App\Models\User::inRandomOrder()->first()?->id ?? \App\Models\User::factory(),
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'alternate_phone' => fake()->optional()->phoneNumber(),
            'date_of_birth' => fake()->date('Y-m-d', '-18 years'),
            'nationality' => fake()->country(),
            'address' => fake()->streetAddress(),
            'city' => fake()->city(),
            'state' => fake()->state(),
            'country' => fake()->country(),
            'preferred_countries' => null, // Will be set after countries are seeded
            'preferred_level' => fake()->randomElement(['Bachelor', 'Master', 'PhD', 'Diploma']),
            'preferred_subjects' => fake()->randomElements(['Engineering', 'Business', 'Medicine', 'Computer Science', 'Arts', 'Law'], 2),
            'status' => fake()->randomElement(['new', 'contacted', 'qualified', 'converted', 'lost']),
            'source' => fake()->randomElement(['walk-in', 'website', 'referral', 'social-media', 'google-ads', 'facebook-ads']),
            'utm_parameters' => fake()->optional()->passthrough([
                'utm_source' => 'google',
                'utm_medium' => 'cpc',
                'utm_campaign' => 'spring-2024',
            ]),
            'notes' => fake()->optional()->paragraph(),
            'lost_reason' => fake()->optional()->sentence(),
            'last_contact_at' => fake()->optional()->dateTimeBetween('-30 days', 'now'),
            'next_follow_up_at' => fake()->optional()->dateTimeBetween('now', '+30 days'),
        ];
    }

    public function newLead(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'new',
            'last_contact_at' => null,
        ]);
    }

    public function qualified(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'qualified',
            'last_contact_at' => now(),
        ]);
    }

    public function lost(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'lost',
            'lost_reason' => fake()->randomElement([
                'Not interested anymore',
                'Went with competitor',
                'Budget constraints',
                'Changed plans',
                'No response',
            ]),
        ]);
    }
}
