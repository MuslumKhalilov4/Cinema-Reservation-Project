<?php

namespace Database\Factories;

use App\Models\Cinema;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Cinema>
 */
class CinemaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->word();
        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'about' => [
                'az' => fake()->paragraph(),
                'en' => fake()->paragraph(),
                'ru' => fake()->paragraph(),
            ],
            'address' => fake()->address(),
            'phone' => '+99450000' . fake()->unique()->numberBetween(1000, 9999),
            'email' => fake()->unique()->safeEmail(),
            'image_url' => null,
            'city' => fake()->city(),
        ];
    }
}
