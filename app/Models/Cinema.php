<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cinema extends Model
{
    use HasFactory, HasTranslations, HasSlug;

    protected $fillable = [
        'name', 
        'slug',
        'about',
        'address',
        'phone',
        'email',
        'image_url',
        'city',
    ];

    protected $translatable = [
        'about',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    public function halls(): HasMany
    {
        return $this->hasMany(Hall::class);
    }
}
