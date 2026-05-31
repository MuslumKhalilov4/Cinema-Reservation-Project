<?php

namespace App\Data\Auth;

use Spatie\LaravelData\Data;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\Rules\Password;

class RegisterData extends Data
{
    public function __construct(
        public string $first_name,
        public string $last_name,
        public string $username,
        public string $email,
        public string $phone,
        public ?UploadedFile $avatar = null,
        public string $password,
    ) {}

    public static function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users'],
            'email' => ['required', 'email', 'unique:users'],
            'phone' => ['required', 'string', 'max:255', 'unique:users'],
            'avatar' => ['nullable', 'file', 'mimes:jpeg,png,jpg,svg', 'max:2048'],
            'password' => ['required', 'string', Password::min(8)->letters()->numbers()->symbols(), 'confirmed'],
        ];
    }
}
