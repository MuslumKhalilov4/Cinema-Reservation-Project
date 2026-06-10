<?php

namespace App\Actions\Auth;

use App\Data\Auth\LoginData;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Exceptions\AuthException;
use App\Services\AuditLogService;

class LoginAction
{
    public function __construct(
        private AuditLogService $auditLogService,
    ) {}

    public function execute(LoginData $data): array
    {
        $user = User::where('email', $data->email)->first();

        if (!$user || !Hash::check($data->password, $user->password)) {
            throw AuthException::invalidCredentials();
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        $this->auditLogService->auditLogLogin($user);

        return [
            'user' => $user,
            'token' => $token,
        ];
    }
}
