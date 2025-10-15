<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Student>
 */
final class StudentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'lead_id' => null,
            'branch_id' => \App\Models\Branch::inRandomOrder()->first()?->id ?? \App\Models\Branch::factory(),
            'assigned_counsellor' => \App\Models\User::inRandomOrder()->first()?->id ?? \App\Models\User::factory(),
            'student_id' => 'STU-'.mb_strtoupper(fake()->unique()->bothify('####??##')),
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'alternate_phone' => fake()->optional()->phoneNumber(),
            'date_of_birth' => fake()->date('Y-m-d', '-20 years'),
            'gender' => fake()->randomElement(['Male', 'Female', 'Other']),
            'nationality' => fake()->country(),
            'passport_number' => mb_strtoupper(fake()->bothify('??#######')),
            'passport_expiry' => fake()->dateTimeBetween('+1 year', '+10 years')->format('Y-m-d'),
            'address' => fake()->streetAddress(),
            'city' => fake()->city(),
            'state' => fake()->state(),
            'country' => fake()->country(),
            'postal_code' => fake()->postcode(),
            'emergency_contact_name' => fake()->name(),
            'emergency_contact_phone' => fake()->phoneNumber(),
            'emergency_contact_relationship' => fake()->randomElement(['Parent', 'Spouse', 'Sibling', 'Friend']),
            'highest_education_level' => fake()->randomElement(['High School', 'Associate Degree', 'Bachelor', 'Master', 'PhD']),
            'field_of_study' => fake()->randomElement(['Engineering', 'Business', 'Computer Science', 'Medicine', 'Arts', 'Law']),
            'gpa' => fake()->randomFloat(2, 2.5, 4.0),
            'academic_history' => [
                [
                    'institution' => fake()->company().' University',
                    'degree' => 'Bachelor of Science',
                    'field' => 'Computer Science',
                    'start_date' => '2018-09',
                    'end_date' => '2022-06',
                    'gpa' => '3.5',
                ],
            ],
            'work_experience' => fake()->optional()->passthrough([
                [
                    'company' => fake()->company(),
                    'position' => fake()->jobTitle(),
                    'start_date' => '2022-07',
                    'end_date' => '2024-06',
                    'description' => fake()->sentence(),
                ],
            ]),
            'english_proficiency' => [
                'test' => fake()->randomElement(['IELTS', 'TOEFL', 'PTE']),
                'score' => fake()->randomElement(['6.5', '7.0', '7.5', '90', '100']),
                'date' => fake()->date('Y-m-d', '-1 year'),
            ],
            'other_tests' => fake()->optional()->passthrough([
                'GRE' => ['score' => '320', 'date' => fake()->date('Y-m-d', '-1 year')],
            ]),
            'notes' => fake()->optional()->paragraph(),
        ];
    }

    public function fromLead(): static
    {
        return $this->state(fn (array $attributes) => [
            'lead_id' => \App\Models\Lead::factory(),
        ]);
    }
}
