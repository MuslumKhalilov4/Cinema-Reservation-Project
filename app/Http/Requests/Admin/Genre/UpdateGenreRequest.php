<?php

namespace App\Http\Requests\Admin\Genre;

use Illuminate\Foundation\Http\FormRequest;

class UpdateGenreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'array', 'max:3'],
            'name.az' => ['sometimes', 'nullable', 'string', 'max:255'],
            'name.en' => ['sometimes', 'nullable', 'string', 'max:255'],
            'name.ru' => ['sometimes', 'nullable', 'string', 'max:255'],
        ];
    }
}
