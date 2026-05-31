<?php

namespace App\Actions\Auth;

use App\Data\Auth\RegisterData;
use App\Services\FileService;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class RegisterAction
{
    public function __construct(
        private FileService $fileService,
    ) {}

    public function execute(RegisterData $data): User
    {
        return DB::transaction(function () use ($data) {
            $avatarUrl = $data->avatar ? $this->fileService->upload($data->avatar, 'avatars') : null;
            $password = Hash::make($data->password);

            $user = User::create([
                'first_name' => $data->first_name,
                'last_name' => $data->last_name,
                'username' => $data->username,
                'email' => $data->email,
                'phone' => $data->phone,
                'avatar_url' => $avatarUrl,
                'password' => $password,
            ]);

            $user->assignRole('user');

            return $user;
        });
    }
}
