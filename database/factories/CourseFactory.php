<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Course>
 */
final class CourseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $levels = ['Bachelor', 'Master', 'PhD', 'Diploma', 'Certificate'];
        $subjects = [
            'Computer Science', 'Business Administration', 'Engineering', 'Medicine',
            'Law', 'Economics', 'Psychology', 'Nursing', 'Education', 'Architecture',
            'Marketing', 'Finance', 'Data Science', 'Artificial Intelligence',
        ];

        $level = fake()->randomElement($levels);
        $subject = fake()->randomElement($subjects);

        $duration = match ($level) {
            'Bachelor' => ['value' => 3, 'unit' => 'years'],
            'Master' => ['value' => fake()->randomElement([1, 2]), 'unit' => 'years'],
            'PhD' => ['value' => fake()->randomElement([3, 4, 5]), 'unit' => 'years'],
            'Diploma' => ['value' => fake()->randomElement([6, 12, 18]), 'unit' => 'months'],
            'Certificate' => ['value' => fake()->randomElement([3, 6]), 'unit' => 'months'],
        };

        return [
            'institution_id' => \App\Models\Institution::inRandomOrder()->first()?->id ?? \App\Models\Institution::factory(),
            'name' => $level.' of '.$subject,
            'code' => mb_strtoupper(fake()->bothify('???###')),
            'description' => fake()->paragraph(3),
            'level' => $level,
            'subject_area' => explode(' ', $subject)[0],
            'specialization' => fake()->optional()->words(2, true),
            'duration_value' => $duration['value'],
            'duration_unit' => $duration['unit'],
            'tuition_fee' => fake()->numberBetween(10000, 50000),
            'fee_currency' => fake()->randomElement(['USD', 'GBP', 'CAD', 'AUD', 'EUR']),
            'fee_period' => 'per year',
            'scholarships' => fake()->optional()->passthrough([
                [
                    'name' => 'Merit Scholarship',
                    'amount' => fake()->numberBetween(2000, 10000),
                    'criteria' => 'GPA above 3.5',
                ],
            ]),
            'intakes' => [
                ['name' => 'Fall', 'start_date' => '2024-09-01'],
                ['name' => 'Spring', 'start_date' => '2025-01-15'],
            ],
            'entry_requirements' => fake()->paragraph(),
            'english_requirement' => fake()->randomElement(['IELTS 6.5', 'TOEFL 90', 'PTE 65', 'Duolingo 110']),
            'other_requirements' => fake()->optional()->passthrough(['GRE' => '310+', 'GMAT' => '650+']),
            'mode_of_study' => fake()->randomElement(['Full-time', 'Part-time', 'Online', 'Hybrid']),
            'career_outcomes' => fake()->paragraph(),
            'course_structure' => fake()->paragraph(),
            'is_active' => true,
            'is_featured' => fake()->boolean(20),
        ];
    }

    public function featured(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_featured' => true,
        ]);
    }

    public function bachelor(): static
    {
        return $this->state(fn (array $attributes) => [
            'level' => 'Bachelor',
            'duration_value' => 3,
            'duration_unit' => 'years',
        ]);
    }

    public function master(): static
    {
        return $this->state(fn (array $attributes) => [
            'level' => 'Master',
            'duration_value' => fake()->randomElement([1, 2]),
            'duration_unit' => 'years',
        ]);
    }
}
