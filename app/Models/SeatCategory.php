<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SeatCategory extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'additional_price', 'color_code'];

    public function seats(): HasMany
    {
        return $this->hasMany(Seat::class);
    }
}
