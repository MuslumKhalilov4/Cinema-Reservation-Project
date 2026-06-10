<?php

namespace Database\Factories;

use App\Models\Genre;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Genre>
 */
class GenreFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $enName = $this->faker->unique()->word();
        return [
            'name' => [
                'az' => $this->faker->word(),
                'en' => $enName,
                'ru' => $this->faker->word(),
            ],
            'slug' => Str::slug($enName),
        ];
    }
}
