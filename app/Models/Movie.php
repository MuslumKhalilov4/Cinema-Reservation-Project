<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Movie extends Model
{
    use HasFactory, HasTranslations, HasSlug;

    protected $fillable = [
        'title',
        'description',
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

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom(function ($model) {
                return $model->getTranslation('title', 'en');
            })
            ->saveSlugsTo('slug');
    }

    public array $translatable = ['title', 'description'];

    public function genres(): BelongsToMany
    {
        return $this->belongsToMany(Genre::class, 'movie_genre');
    }

    public function cast(): BelongsToMany
    {
        return $this->belongsToMany(Actor::class, 'cast')->withPivot('character_name');
    }
}
