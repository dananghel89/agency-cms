<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models$2>
 */
class TeamFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'profession' => fake()->jobTitle(),
            'social_links' => ['linkedin' => 'https://www.linkedin.com/'],
            'position' => fake()->numberBetween(0, 100),
            'status' => 'publish',
        ];
    }

    /**
     * Indicate that the item is a draft.
     */
    public function draft(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'draft',
        ]);
    }
}
