<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Cinema;
use App\Models\Hall;
use App\Models\SeatCategory;
use App\Models\Seat;

class CinemaHallSeatSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        SeatCategory::factory()->count(3)->create();
        Cinema::factory()->count(3)->create()->each(function ($cinema) {
            Hall::factory()->count(3)->create([
                'cinema_id' => $cinema->id,
            ])->each(function ($hall) {
                $rows = ['A', 'B', 'C', 'D', 'E'];
                $seatPerRow = 10;

                foreach ($rows as $row) {
                    for ($i = 1; $i <= $seatPerRow; $i++) {
                        Seat::create([
                            'hall_id' => $hall->id,
                            'seat_category_id' => SeatCategory::pluck('id')->random(),
                            'row' => $row,
                            'seat_number' => $i,
                        ]);
                    }
                }
            });
        });
    }
}
