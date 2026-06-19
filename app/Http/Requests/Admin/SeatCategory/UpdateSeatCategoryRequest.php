<?php

namespace App\Http\Requests\Admin\SeatCategory;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSeatCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'string', 'max:255'],
            'additional_price' => ['sometimes', 'numeric', 'min:0'],
            'color_code' => ['sometimes', 'string', 'max:255', Rule::unique('seat_categories', 'color_code')->ignore($this->seat_category->id)],
        ];
    }
}
