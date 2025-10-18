<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Organization>
 */
final class OrganizationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $companyName = fake()->company();
        $slug = Str::slug($companyName).'-'.fake()->unique()->numberBetween(100, 999);

        return [
            'name' => $companyName.' Education Consultancy',
            'slug' => $slug,
            'domain' => null,
            'email' => fake()->companyEmail(),
            'phone' => fake()->phoneNumber(),
            'address' => fake()->address(),
            'logo' => null,
            'color_scheme' => [
                'primary' => fake()->hexColor(),
                'secondary' => fake()->hexColor(),
                'accent' => fake()->hexColor(),
            ],
            'email_settings' => [
                'from_name' => $companyName,
                'from_email' => fake()->companyEmail(),
                'smtp_configured' => false,
            ],
            'subscription_plan' => fake()->randomElement(['trial', 'basic', 'premium', 'enterprise']),
            'subscription_expires_at' => fake()->dateTimeBetween('now', '+1 year'),
            'is_active' => true,
            'settings' => [
                'timezone' => fake()->timezone(),
                'currency' => fake()->randomElement(['USD', 'GBP', 'EUR', 'AUD', 'CAD']),
                'date_format' => 'Y-m-d',
                'time_format' => 'H:i:s',
            ],
        ];
    }

    /**
     * Indicate that the organization is on trial.
     */
    public function trial(): static
    {
        return $this->state(fn (array $attributes) => [
            'subscription_plan' => 'trial',
            'subscription_expires_at' => now()->addDays(30),
        ]);
    }

    /**
     * Indicate that the organization is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}
