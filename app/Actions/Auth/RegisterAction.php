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
            $userData = $data->toArray();

            $userData['avatar_url'] = $data->avatar ? $this->fileService->upload($data->avatar, 'avatars') : null;
            $userData['password'] = Hash::make($data->password);

            $user = User::create($userData);

            $user->assignRole('user');

            $this->logActivity($user);

            return $user;
        });
    }

    public function logActivity(User $user): void
    {
        activity()
            ->useLog('auth')
            ->event('register')
            ->performedOn($user)
            ->causedBy($user)
            ->withProperties([
                'username' => $user->username,
                'email' => $user->email,
                'phone' => $user->phone,
            ])
            ->log('New user registered. Username: ' . $user->username);
    }
}
