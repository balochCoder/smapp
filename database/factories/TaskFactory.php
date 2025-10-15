<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
final class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $taskTitles = [
            'Follow up with student',
            'Review application documents',
            'Prepare visa application',
            'Submit application to university',
            'Upload transcripts',
            'Request reference letters',
            'Schedule counseling session',
            'Process payment',
            'Update student profile',
            'Send offer letter',
        ];

        return [
            'title' => fake()->randomElement($taskTitles),
            'description' => fake()->optional()->paragraph(),
            'created_by' => \App\Models\User::inRandomOrder()->first()?->id ?? \App\Models\User::factory(),
            'assigned_to' => \App\Models\User::inRandomOrder()->first()?->id ?? \App\Models\User::factory(),
            'branch_id' => \App\Models\Branch::inRandomOrder()->first()?->id ?? \App\Models\Branch::factory(),
            'application_id' => fake()->optional()->passthrough(\App\Models\Application::inRandomOrder()->first()?->id),
            'lead_id' => fake()->optional()->passthrough(\App\Models\Lead::inRandomOrder()->first()?->id),
            'student_id' => fake()->optional()->passthrough(\App\Models\Student::inRandomOrder()->first()?->id),
            'parent_task_id' => null,
            'category' => fake()->randomElement(['Documentation', 'Follow-up', 'Internal', 'Urgent']),
            'priority' => fake()->randomElement(['low', 'medium', 'high', 'critical']),
            'status' => fake()->randomElement(['pending', 'in-progress', 'completed', 'cancelled']),
            'due_date' => fake()->dateTimeBetween('now', '+30 days'),
            'completed_at' => null,
            'completed_by' => null,
            'completion_notes' => null,
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
            'completion_notes' => fake()->sentence(),
        ]);
    }

    public function urgent(): static
    {
        return $this->state(fn (array $attributes) => [
            'priority' => 'critical',
            'category' => 'Urgent',
            'due_date' => now()->addDays(1),
        ]);
    }
}
