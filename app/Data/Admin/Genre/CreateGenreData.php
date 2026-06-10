<?php

namespace App\Data\Admin\Genre;

use Spatie\LaravelData\Data;

class CreateGenreData extends Data
{
    public function __construct(
        public array $name,
    ) {}

    public static function rules(): array
    {
        return [
            'name' => ['required', 'array', 'max:3'],
            'name.az' => ['required', 'string', 'max:255'],
            'name.en' => ['required', 'string', 'max:255'],
            'name.ru' => ['required', 'string', 'max:255'],
        ];
    }
}
