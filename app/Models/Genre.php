<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Genre extends Model
{
    use HasFactory, HasTranslations;

    protected $fillable = ['name', 'slug'];

    public array $translatable = ['name'];
}
