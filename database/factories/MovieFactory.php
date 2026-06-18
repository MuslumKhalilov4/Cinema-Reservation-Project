<?php

namespace Database\Factories;

use App\Models\Movie;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Movie>
 */
class MovieFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $enTitle = $this->faker->unique()->word();
        return [
            'title' => [
                'az' => $this->faker->word(),
                'en' => $enTitle,
                'ru' => $this->faker->word(),
            ],
            'description' => [
                'az' => $this->faker->paragraph(),
                'en' => $this->faker->paragraph(),
                'ru' => $this->faker->paragraph(),
            ],
            'slug' => Str::slug($enTitle),
            'poster_url' => null,
            'trailer_url' => null,
            'duration' => $this->faker->numberBetween(60, 180),
            'release_date' => $this->faker->date(),
            'country' => $this->faker->country(),
            'language' => $this->faker->languageCode(),
            'director' => $this->faker->name(),
            'age_limit' => $this->faker->randomElement([0, 6, 12, 16, 18]),
        ];
    }
}
