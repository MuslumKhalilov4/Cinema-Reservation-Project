<?php

namespace App\Observers;

use App\Models\Seat;
use App\Models\Hall;

class SeatObserver
{
    public function created(Seat $seat): void
    {
        $this->updateHallStatistics($seat->hall_id);
    }

    public function updated(Seat $seat): void
    {
        $this->updateHallStatistics($seat->hall_id);

        if ($seat->wasChanged('hall_id')) {
            $oldHallId = $seat->getOriginal('hall_id');
            $this->updateHallStatistics($oldHallId);
        }
    }

    public function deleted(Seat $seat): void
    {
        $this->updateHallStatistics($seat->hall_id);
    }

    private function updateHallStatistics(int $hall_id): void
    {
        $hall = Hall::find($hall_id);
        if ($hall) {
            $hall->update([
                'capacity' => $hall->seats()->active()->count(),
                'row_count' => $hall->seats()->active()->distinct()->count('row'),
            ]);
        }
    }
}
