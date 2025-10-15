<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FollowUp>
 */
final class FollowUpFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $followUpDate = fake()->dateTimeBetween('now', '+30 days');

        return [
            'lead_id' => fake()->optional()->passthrough(\App\Models\Lead::inRandomOrder()->first()?->id),
            'student_id' => fake()->optional()->passthrough(\App\Models\Student::inRandomOrder()->first()?->id),
            'application_id' => fake()->optional()->passthrough(\App\Models\Application::inRandomOrder()->first()?->id),
            'created_by' => \App\Models\User::inRandomOrder()->first()?->id ?? \App\Models\User::factory(),
            'assigned_to' => \App\Models\User::inRandomOrder()->first()?->id ?? \App\Models\User::factory(),
            'type' => fake()->randomElement(['call', 'email', 'meeting', 'whatsapp']),
            'subject' => fake()->sentence(6),
            'notes' => fake()->paragraph(),
            'follow_up_date' => $followUpDate->format('Y-m-d'),
            'follow_up_time' => fake()->time('H:i'),
            'status' => fake()->randomElement(['pending', 'completed', 'overdue', 'cancelled']),
            'outcome' => fake()->optional()->sentence(),
            'completed_at' => null,
            'completed_by' => null,
            'reminder_sent' => false,
            'reminder_sent_at' => null,
        ];
    }

    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pending',
            'completed_at' => null,
            'completed_by' => null,
        ]);
    }

    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'completed',
            'completed_at' => now(),
            'completed_by' => \App\Models\User::factory(),
            'outcome' => fake()->sentence(),
        ]);
    }

    public function overdue(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'overdue',
            'follow_up_date' => now()->subDays(rand(1, 7))->format('Y-m-d'),
        ]);
    }

    public function forLead(): static
    {
        return $this->state(fn (array $attributes) => [
            'lead_id' => \App\Models\Lead::factory(),
            'student_id' => null,
            'application_id' => null,
        ]);
    }

    public function forApplication(): static
    {
        return $this->state(fn (array $attributes) => [
            'lead_id' => null,
            'student_id' => null,
            'application_id' => \App\Models\Application::factory(),
        ]);
    }
}
