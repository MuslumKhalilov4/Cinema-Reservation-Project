<?php

namespace App\Http\Requests\Admin\Genre;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateGenreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $locales = config('app.supported_locales', []);

        return [
            'name' => ['sometimes', 'array', Rule::array($locales)],
            'name.*' => ['sometimes', 'nullable', 'string', 'max:255'],
        ];
    }
}
