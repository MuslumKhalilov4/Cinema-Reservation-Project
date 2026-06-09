<?php

namespace App\Actions\Auth;

use App\Data\Auth\LoginData;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Exceptions\AuthException;
use App\Services\LogService;
use App\Services\AuditLogService;

class LoginAction
{
    public function __construct(
        private LogService $logService,
        private AuditLogService $auditLogService,
    ) {}

    public function execute(LoginData $data): array
    {
        try {
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
        } catch (\Throwable $e) {
            if ($e instanceof AuthException) {
                throw $e;
            }
            $this->logService->logFailure($e, ['email' => $data->email], 'Login Exception');
            throw AuthException::unexpecedAuthException();
        }
    }
}
