<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Genre;

class GenreSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        Genre::factory()->count(10)->create();
    }
}
