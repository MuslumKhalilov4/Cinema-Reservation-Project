<?php

namespace App\Http\Requests\Admin\Genre;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateGenreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $locales = config('app.supported_locales', []);

        return [
            'name' => ['required', 'array', Rule::array($locales)],
            'name.*' => ['required', 'string', 'max:255'],
        ];
    }
}
