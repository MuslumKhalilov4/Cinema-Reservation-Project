<?php

namespace App\Data\Admin\Genre;

use Spatie\LaravelData\Data;
use App\Models\Genre;

class GenreData extends Data
{
    public function __construct(
        public array $name,
        public string $slug,
        public string $created_at,
        public string $updated_at,
    ) {}

    public static function fromModel(Genre $genre): self
    {
        return new self(
            name: $genre->getTranslations('name'),
            slug: $genre->slug,
            created_at: $genre->created_at->toDateTimeString(),
            updated_at: $genre->updated_at->toDateTimeString(),
        );
    }
}
