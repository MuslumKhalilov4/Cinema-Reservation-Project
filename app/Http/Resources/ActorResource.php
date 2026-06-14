<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ActorResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'full_name' => $this->first_name . ' ' . $this->last_name,
            'biography' => $this->getTranslations('biography'),
            'birth_date' => $this->birth_date,
            'nationality' => $this->nationality,
            'place_of_birth' => $this->place_of_birth,
            'height' => $this->height,
            'picture_url' => $this->picture_url,
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
        ];
    }
}
