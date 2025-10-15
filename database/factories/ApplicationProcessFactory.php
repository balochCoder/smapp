<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\ApplicationProcess;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ApplicationProcess>
 */
final class ApplicationProcessFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $stages = [
            'Application Submission',
            'Document Verification',
            'Offer Received',
            'Acceptance Letter',
            'Visa Application',
            'Visa Decision',
            'Pre-Departure',
            'Arrival',
        ];

        return [
            'parent_id' => null,
            'name' => fake()->randomElement($stages),
            'description' => fake()->optional()->sentence(),
            'order' => fake()->numberBetween(1, 10),
            'is_active' => true,
        ];
    }

    public function withParent(?string $parentId = null): static
    {
        return $this->state(fn (array $attributes) => [
            'parent_id' => $parentId ?? ApplicationProcess::factory(),
        ]);
    }
}
