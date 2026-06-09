<?php

namespace App\Data\User;

use Spatie\LaravelData\Data;
use Illuminate\Validation\Rules\Password;

class ChangePasswordData extends Data
{
    public function __construct(
        public string $old_password,
        public string $new_password,
    ) {}

    public static function rules(): array
    {
        return [
            'old_password' => ['required', 'string'],
            'new_password' => ['required', 'string', Password::min(8)->letters()->numbers()->symbols(), 'confirmed'],
        ];
    }
}
