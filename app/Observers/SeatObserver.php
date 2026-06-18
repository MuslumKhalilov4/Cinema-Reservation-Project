<?php

namespace App\Observers;

use App\Models\Seat;

class SeatObserver
{
    public function created(Seat $seat): void
    {
        $this->updateHallStatistics($seat);
    }

    public function updated(Seat $seat): void
    {
        $this->updateHallStatistics($seat);
    }

    public function deleted(Seat $seat): void
    {
        $this->updateHallStatistics($seat);
    }

    private function updateHallStatistics(Seat $seat): void
    {
        $hall = $seat->hall;
        if ($hall) {
            $hall->update([
                'capacity' => $hall->seats->active()->count(),
                'row_count' => $hall->seats->active()->distinct()->count('row'),
            ]);
        }
    }
}
