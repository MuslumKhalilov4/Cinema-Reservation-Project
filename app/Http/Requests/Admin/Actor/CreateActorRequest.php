<?php

namespace App\Http\Requests\Admin\Actor;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;

class CreateActorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $locales = config('app.supported_locales', []);

        return [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'biography' => ['required', 'array', Rule::array($locales)],
            'biography.*' => ['required', 'string', 'max:1000'],
            'birth_date' => ['required', 'date'],
            'nationality' => ['required', 'string', 'max:255'],
            'place_of_birth' => ['required', 'string', 'max:255'],
            'height' => ['required', 'integer', 'min:100', 'max:250'],
            'picture' => ['required', 'file', File::types(['jpeg', 'png', 'jpg', 'svg'])->max(2048)],
        ];
    }
}
