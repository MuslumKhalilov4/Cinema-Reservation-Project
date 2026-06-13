<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Actor extends Model
{
    use HasFactory, HasTranslations;

    protected $fillable = [
        'first_name',
        'last_name',
        'biography',
        'birth_date',
        'nationality',
        'place_of_birth',
        'height',
        'picture_url',
    ];

    public array $translatable = ['biography'];
}
