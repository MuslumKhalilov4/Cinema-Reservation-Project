<?php

namespace App\Http\Requests\Admin\Hall;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateHallRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'string', 'max:255'],
            'cinema_id' => ['sometimes', Rule::exists('cinemas', 'id')],
        ];
    }
}
