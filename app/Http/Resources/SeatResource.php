<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SeatResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'hall_id' => $this->hall_id,
            'seat_category_id' => $this->seat_category_id,
            'row' => $this->row,
            'seat_number' => $this->seat_number,
            'is_active' => $this->is_active,
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
        ];
    }
}
