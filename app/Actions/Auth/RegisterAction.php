<?php

namespace App\Actions\Auth;

use App\Data\Auth\RegisterData;
use App\Services\FileService;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Services\LogService;
use App\Exceptions\AuthException;
use Illuminate\Auth\Events\Registered;

class RegisterAction
{
    public function __construct(
        private FileService $fileService,
        private LogService $logService,
    ) {}

    public function execute(RegisterData $data): array
    {
        DB::beginTransaction();
        try {
            $userData = $data->toArray();

            $userData['avatar_url'] = $data->avatar ? $this->fileService->upload($data->avatar, 'avatars') : null;
            $userData['password'] = Hash::make($data->password);

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
            $this->logService->logFailure('Register Exception', $e, ['email' => $data->email]);
            DB::rollBack();
            throw AuthException::unexpecedAuthException();
        }
    }
}
