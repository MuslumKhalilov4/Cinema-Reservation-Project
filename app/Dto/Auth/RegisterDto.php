<?php

namespace App\Dto\Auth;

use App\Http\Requests\Auth\RegisterRequest;
use Illuminate\Http\UploadedFile;

class RegisterDto
{
    public function __construct(
        public string $first_name,
        public string $last_name,
        public string $username,
        public string $email,
        public string $phone,
        public string $password,
        public ?UploadedFile $avatar = null,
    ) {}

    public static function fromRequest(RegisterRequest $request): self
    {
        return new self(... $request->validated());
    }

    public function toArray(): array
    {
        return array_filter(get_object_vars($this), fn($value) => $value !== null);
    }
}
