<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Actor;

class ActorSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        Actor::factory()->count(100)->create();
    }
}
