<?php

namespace Database\Factories;

use App\Models\SeatCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SeatCategory>
 */
class SeatCategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->word(),
            'additional_price' => fake()->randomElement([0, 1, 2, 3]),
            'color_code' => fake()->unique()->hexColor(),
        ];
    }
}
