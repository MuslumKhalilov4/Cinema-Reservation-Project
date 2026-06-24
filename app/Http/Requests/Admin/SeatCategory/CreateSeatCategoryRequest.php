<?php

namespace App\Http\Requests\Admin\SeatCategory;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateSeatCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'additional_price' => ['required', 'numeric', 'min:0'],
            'color_code' => ['required', 'string', 'max:255', Rule::unique('seat_categories', 'color_code')],
        ];
    }
}
