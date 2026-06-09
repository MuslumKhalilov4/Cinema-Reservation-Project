<?php

namespace App\Data\User;

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;

class UpdateProfileData extends Data
{
    public function __construct(
        public string|Optional $first_name,
        public string|Optional $last_name,
        public string|Optional $username,
        public string|Optional $email,
        public string|Optional $phone,
    ) {}

    public static function rules(): array
    {
        $userId = Auth::id();

        return [
            'first_name' => ['sometimes', 'string', 'max:255'],
            'last_name' => ['sometimes', 'string', 'max:255'],
            'username' => ['sometimes', 'string', 'max:255', Rule::unique('users', 'username')->ignore($userId)],
            'email' => ['sometimes', 'email', Rule::unique('users', 'email')->ignore($userId)],
            'phone' => ['sometimes', 'string', 'max:255', Rule::unique('users', 'phone')->ignore($userId)],
        ];
    }
}
