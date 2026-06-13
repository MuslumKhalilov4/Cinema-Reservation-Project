<?php

namespace App\Dto\User;

use App\Http\Requests\User\UpdateProfileRequest;

class UpdateProfileDto
{
    public function __construct(
        public ?string $first_name = null,
        public ?string $last_name = null,
        public ?string $username = null,
        public ?string $email = null,
        public ?string $phone = null,
    ) {}

    public static function fromRequest(UpdateProfileRequest $request): self
    {
        return new self(... $request->validated());
    }

    public function toArray(): array
    {
        return array_filter(get_object_vars($this), fn($value) => $value !== null);
    }
}
