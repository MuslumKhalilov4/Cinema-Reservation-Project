<?php

namespace App\Http\Requests\Admin\Movie;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Enums\MovieStatusEnum;

class FilterMovieRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'search' => ['sometimes', 'nullable', 'string', 'max:255'],
            'genre_id' => ['sometimes', 'nullable', 'integer', 'exists:genres,id'],
            'status' => ['sometimes', 'nullable', Rule::enum(MovieStatusEnum::class)],
            'sort' => ['sometimes', 'nullable', 'string', 'in:rating,release_date,duration'],
            'sort_direction' => ['sometimes', 'nullable', 'string', 'in:asc,desc'],
            'per_page' => ['sometimes', 'nullable', 'integer', 'min:1', 'max:100'],
        ];
    }
}
