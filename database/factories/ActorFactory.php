<?php

namespace Database\Factories;

use App\Models\Actor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Actor>
 */
class ActorFactory extends Factory
{
    public function definition(): array
    {
        return [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'biography' => [
                'az' => fake()->paragraph(),
                'en' => fake()->paragraph(),
                'ru' => fake()->paragraph(),
            ],
            'birth_date' => fake()->dateTimeBetween('-100 years', '-18 years')->format('Y-m-d'),
            'nationality' => fake()->country(),
            'place_of_birth' => fake()->city(),
            'height' => fake()->numberBetween(150, 200),
            'picture_url' => null,
        ];
    }
}
