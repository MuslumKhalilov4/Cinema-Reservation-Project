<?php

namespace App\Http\Requests\Admin\Seat;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSeatRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'hall_id' => ['sometimes', Rule::exists('halls', 'id')],
            'seat_category_id' => ['sometimes', Rule::exists('seat_categories', 'id')],
            'row' => ['sometimes', 'string', 'size:1'],
            'seat_number' => [
                'sometimes',
                'integer',
                'min:1',
                Rule::unique('seats')->where(fn ($query) => $query
                    ->where('hall_id', $this->input('hall_id', $this->seat->hall_id))
                    ->where('row', $this->input('row', $this->seat->row)))
                    ->ignore($this->seat->id),
            ],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }
}
