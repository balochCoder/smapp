<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ApplicationProcess>
 */
final class ApplicationProcessFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->words(3, true),
            'color' => fake()->randomElement(['blue', 'green', 'yellow', 'red', 'purple', 'orange', 'gray']),
            'order' => fake()->numberBetween(1, 100),
        ];
    }
}
