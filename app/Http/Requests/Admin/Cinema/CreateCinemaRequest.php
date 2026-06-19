<?php

namespace App\Http\Requests\Admin\Cinema;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;

class CreateCinemaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $locales = config('app.supported_locales', []);
        
        return [
            'name' => ['required', 'string', 'max:255'],
            'about' => ['required', 'array', Rule::array($locales)],
            'about.*' => ['required', 'string', 'max:1000'],
            'address' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('cinemas', 'email')],
            'image' => ['required', 'file', File::types(['jpeg', 'png', 'jpg', 'svg'])->max(2048)],
            'city' => ['required', 'string', 'max:255'],
        ];
    }
}
