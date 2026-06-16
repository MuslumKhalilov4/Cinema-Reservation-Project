<?php

namespace App\Http\Requests\Admin\Movie;

use App\Enums\MovieStatusEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;

class CreateMovieRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $locales = config('app.supported_locales', []);

        return [
            'title' => ['required', 'array', Rule::array($locales)],
            'title.*' => ['required', 'string', 'max:255'],
            'description' => ['required', 'array', Rule::array($locales)],
            'description.*' => ['required', 'string', 'max:1000'],
            'poster_url' => ['file', File::types(['jpeg', 'png', 'jpg', 'svg'])->max(2048)],
            'trailer_url' => ['nullable', 'url', 'max:255'],
            'duration' => ['required', 'integer', 'min:60', 'max:180'],
            'release_date' => ['required', 'date'],
            'country' => ['required', 'string', 'max:255'],
            'language' => ['required', 'string', 'max:255'],
            'director' => ['required', 'string', 'max:255'],
            'age_limit' => ['required', 'integer', 'min:0', 'max:18'],
            'is_featured' => ['required', 'boolean'],
            'status' => ['required', Rule::enum(MovieStatusEnum::class)],
            'genre_ids' => ['required', 'array', Rule::exists('genres', 'id')],
            'actor_ids' => ['required', 'array', Rule::exists('actors', 'id')],
        ];
    }
}
