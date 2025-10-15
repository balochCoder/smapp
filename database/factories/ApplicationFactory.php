<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Application>
 */
final class ApplicationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $statuses = ['draft', 'submitted', 'under-review', 'offer', 'conditional-offer', 'rejected', 'accepted', 'visa-applied', 'visa-granted', 'enrolled'];
        $status = fake()->randomElement($statuses);

        return [
            'application_number' => 'APP-'.mb_strtoupper(fake()->unique()->bothify('####-??-####')),
            'student_id' => \App\Models\Student::inRandomOrder()->first()?->id ?? \App\Models\Student::factory(),
            'branch_id' => \App\Models\Branch::inRandomOrder()->first()?->id ?? \App\Models\Branch::factory(),
            'country_id' => \App\Models\Country::inRandomOrder()->first()?->id ?? \App\Models\Country::factory(),
            'institution_id' => \App\Models\Institution::inRandomOrder()->first()?->id ?? \App\Models\Institution::factory(),
            'course_id' => \App\Models\Course::inRandomOrder()->first()?->id ?? \App\Models\Course::factory(),
            'assigned_officer' => \App\Models\User::inRandomOrder()->first()?->id ?? \App\Models\User::factory(),
            'intake' => fake()->randomElement(['Fall 2024', 'Spring 2025', 'Fall 2025']),
            'intake_date' => fake()->date('Y-m-d', '+6 months'),
            'status' => $status,
            'current_stage' => fake()->randomElement(['Application Submission', 'Document Review', 'Offer Processing', 'Visa Application']),
            'workflow_stages' => [
                ['stage' => 'Application Submission', 'completed' => true, 'date' => now()->subDays(30)->format('Y-m-d')],
                ['stage' => 'Document Review', 'completed' => true, 'date' => now()->subDays(20)->format('Y-m-d')],
                ['stage' => 'Offer Processing', 'completed' => false, 'date' => null],
            ],
            'application_date' => fake()->dateTimeBetween('-3 months', 'now'),
            'decision_date' => in_array($status, ['offer', 'rejected']) ? fake()->dateTimeBetween('-1 month', 'now') : null,
            'decision_notes' => fake()->optional()->sentence(),
            'document_checklist' => [
                ['document' => 'Passport', 'status' => 'received'],
                ['document' => 'Transcripts', 'status' => 'received'],
                ['document' => 'English Test', 'status' => 'pending'],
                ['document' => 'SOP', 'status' => 'received'],
            ],
            'conditional_offer' => fake()->boolean(30),
            'offer_conditions' => fake()->optional()->passthrough(['Complete English test', 'Submit final transcripts']),
            'offer_letter_path' => null,
            'offer_expiry_date' => fake()->optional()->dateTimeBetween('+1 month', '+3 months'),
            'application_fee' => fake()->randomElement([0, 50, 100, 150]),
            'application_fee_paid' => fake()->boolean(70),
            'tuition_deposit' => fake()->randomElement([0, 1000, 2000, 5000]),
            'tuition_deposit_paid' => fake()->boolean(40),
            'visa_status' => fake()->randomElement(['applied', 'approved', 'rejected', 'not-required', null]),
            'visa_application_date' => fake()->optional()->dateTimeBetween('-2 months', 'now'),
            'visa_decision_date' => fake()->optional()->dateTimeBetween('-1 month', 'now'),
            'notes' => fake()->optional()->paragraph(),
            'internal_notes' => fake()->optional()->paragraph(),
        ];
    }

    public function draft(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'draft',
            'application_date' => null,
            'decision_date' => null,
        ]);
    }

    public function submitted(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'submitted',
            'application_date' => now(),
        ]);
    }

    public function offered(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'offer',
            'application_date' => now()->subDays(30),
            'decision_date' => now()->subDays(10),
        ]);
    }
}
