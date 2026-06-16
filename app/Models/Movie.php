<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Movie extends Model
{
    use HasFactory, HasTranslations;

    protected $fillable = [
        'title',
        'description',
        'slug',
        'poster_url',
        'trailer_url',
        'duration',
        'release_date',
        'country',
        'language',
        'director',
        'rating_count',
        'rating',
        'age_limit',
        'is_featured',
        'status',
    ];
    
    public array $translatable = ['title', 'description'];

    public function genres(): BelongsToMany
    {
        return $this->belongsToMany(Genre::class, 'movie_genre');
    }

    public function cast(): BelongsToMany
    {
        return $this->belongsToMany(Actor::class, 'cast');
    }
}
