<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class Seat extends Model
{
    use HasFactory;

    protected $fillable = ['hall_id', 'seat_category_id', 'row', 'seat_number', 'is_active'];

    public function hall(): BelongsTo
    {
        return $this->belongsTo(Hall::class);
    }

    public function seatCategory(): BelongsTo
    {
        return $this->belongsTo(SeatCategory::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }
}
