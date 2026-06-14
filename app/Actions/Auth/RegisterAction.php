<?php

namespace App\Actions\Auth;

use App\Dto\Auth\RegisterDto;
use App\Services\FileService;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Auth\Events\Registered;

class RegisterAction
{
    public function __construct(
        private FileService $fileService,
    ) {}

    public function execute(RegisterDto $data): array
    {
        DB::beginTransaction();

        try {
            $userData = $data->toArray();

            $userData['avatar_url'] = $data->avatar ? $this->fileService->upload($data->avatar, 'avatars') : null;
            $userData['password'] = Hash::make($data->password);

            unset($userData['avatar']);

            $user = User::create($userData);

            $user->assignRole('user');

            $token = $user->createToken('auth_token')->plainTextToken;

            DB::commit();

            event(new Registered($user));

            return [
                'user' => $user,
                'token' => $token,
            ];
        } catch (\Throwable $e) {
            DB::rollBack();

            throw $e;
        }
    }
}
