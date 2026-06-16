<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\{Movie, Actor, Genre};

class MovieSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $actorIds = Actor::pluck('id');
        $genreIds = Genre::pluck('id');
        
        Movie::factory()->count(100)->create()->each(function ($movie) use ($actorIds, $genreIds) {
            $randomActors = $actorIds->random(rand(2, 8));

            foreach ($randomActors as $actorId){
                $movie->cast()->attach($actorId, ['character_name' => fake()->name()]);
            }
            $movie->genres()->attach($genreIds->random(rand(1, 3)));
        });
    }
}
