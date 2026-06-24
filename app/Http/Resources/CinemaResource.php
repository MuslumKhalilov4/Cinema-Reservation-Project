<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CinemaResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name,
            'slug' => $this->slug,
            'about' => $this->getTranslation('about', app()->getLocale()),
            'address' => $this->address,
            'phone' => $this->phone,
            'email' => $this->email,
            'image_url' => $this->image_url,
            'city' => $this->city,
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
        ];
    }
}
