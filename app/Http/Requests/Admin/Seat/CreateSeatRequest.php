<?php

namespace App\Http\Requests\Admin\Seat;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateSeatRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'hall_id' => ['required', Rule::exists('halls', 'id')],
            'seat_category_id' => ['required', Rule::exists('seat_categories', 'id')],
            'row' => ['required', 'string', 'size:1'],
            'seat_number' => [
                'required',
                'integer',
                'min:1',
                Rule::unique('seats')->where(fn ($query) => $query
                    ->where('hall_id', $this->input('hall_id'))
                    ->where('row', $this->input('row'))),
            ],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }
}
