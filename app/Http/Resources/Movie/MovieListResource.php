<?php

namespace App\Http\Resources\Movie;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MovieListResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->getTranslation('title', app()->getLocale()),
            'description' => $this->getTranslation('description', app()->getLocale()),
            'slug' => $this->slug,
            'poster_url' => $this->poster_url,
            'trailer_url' => $this->trailer_url,
            'duration' => $this->duration,
            'release_date' => $this->release_date,
            'country' => $this->country,
            'language' => $this->language,
            'director' => $this->director,
            'rating_count' => $this->rating_count,
            'rating' => $this->rating,
            'age_limit' => $this->age_limit,
            'is_featured' => $this->is_featured,
            'status' => $this->status,
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
        ];
    }
}
