<?php

namespace App\Http\Requests\Admin\Actor;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;

class UpdateActorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $locales = config('app.supported_locales', []);

        return [
            'first_name' => ['sometimes', 'string', 'max:255'],
            'last_name' => ['sometimes', 'string', 'max:255'],
            'biography' => ['sometimes', 'array', Rule::array($locales)],
            'biography.*' => ['required', 'string', 'max:1000'],
            'birth_date' => ['sometimes', 'date'],
            'nationality' => ['sometimes', 'string', 'max:255'],
            'place_of_birth' => ['sometimes', 'string', 'max:255'],
            'height' => ['sometimes', 'integer', 'min:100', 'max:250'],
            'picture' => ['sometimes', 'file', File::types(['jpeg', 'png', 'jpg', 'svg'])->max(2048)],
        ];
    }
}
