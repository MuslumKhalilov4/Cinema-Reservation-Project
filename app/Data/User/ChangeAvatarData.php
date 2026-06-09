<?php

namespace App\Data\User;

use Spatie\LaravelData\Data;
use Illuminate\Http\UploadedFile;

class ChangeAvatarData extends Data
{
    public function __construct(
        public UploadedFile $avatar,
    ) {}

    public static function rules(): array
    {
        return [
            'avatar' => ['required', 'file', 'mimes:jpeg,png,jpg,svg', 'max:2048'],
        ];
    }
}
