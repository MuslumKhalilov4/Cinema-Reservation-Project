<?php

namespace App\Http\Requests\Admin\Cinema;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;

class UpdateCinemaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $locales = config('app.supported_locales', []);
        
        return [
            'name' => ['sometimes', 'string', 'max:255'],
            'about' => ['sometimes', 'array', Rule::array($locales)],
            'about.*' => ['sometimes', 'string', 'max:1000'],
            'address' => ['sometimes', 'string', 'max:255'],
            'phone' => ['sometimes', 'string', 'max:255'],
            'email' => ['sometimes', 'email', 'max:255', Rule::unique('cinemas', 'email')->ignore($this->cinema->id)],
            'image' => ['sometimes', 'file', File::types(['jpeg', 'png', 'jpg', 'svg'])->max(2048)],
        ];
    }
}
