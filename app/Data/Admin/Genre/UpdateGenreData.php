<?php

namespace App\Data\Admin\Genre;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;

class UpdateGenreData extends Data
{
    public function __construct(
        public array|Optional $name,
    ) {}

    public static function rules(): array
    {
        return [
            'name' => ['sometimes', 'array', 'max:3'],
            'name.az' => ['sometimes','nullable', 'string', 'max:255'],
            'name.en' => ['sometimes', 'nullable', 'string', 'max:255'],
            'name.ru' => ['sometimes', 'nullable', 'string', 'max:255'],
        ];
    }
}
