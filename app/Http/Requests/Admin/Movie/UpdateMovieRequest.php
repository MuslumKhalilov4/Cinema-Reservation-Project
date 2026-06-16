<?php

namespace App\Http\Requests\Admin\Movie;

use App\Enums\MovieStatusEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;

class UpdateMovieRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $locales = config('app.supported_locales', []);

        return [
            'title' => ['sometimes', 'array', Rule::array($locales)],
            'title.*' => ['sometimes', 'string', 'max:255'],
            'description' => ['sometimes', 'array', Rule::array($locales)],
            'description.*' => ['sometimes', 'string', 'max:1000'],
            'poster_url' => ['sometimes', 'nullable', 'file', File::types(['jpeg', 'png', 'jpg', 'svg'])->max(2048)],
            'trailer_url' => ['sometimes', 'url', 'max:255'],
            'duration' => ['sometimes', 'integer', 'min:60', 'max:180'],
            'release_date' => ['sometimes', 'date'],
            'country' => ['sometimes', 'string', 'max:255'],
            'language' => ['sometimes', 'string', 'max:255'],
            'director' => ['sometimes', 'string', 'max:255'],
            'age_limit' => ['sometimes', 'integer', 'min:0', 'max:18'],
            'is_featured' => ['sometimes', 'boolean'],
            'status' => ['sometimes', Rule::enum(MovieStatusEnum::class)],
            'genre_ids' => ['sometimes', 'array', Rule::exists('genres', 'id')],
            'actor_ids' => ['sometimes', 'array', Rule::exists('actors', 'id')],
        ];
    }
}
