<?php

namespace App\Actions\User;

use App\Data\User\ChangePasswordData;
use Illuminate\Support\Facades\Hash;
use App\Services\LogService;
use Symfony\Component\HttpKernel\Exception\HttpException;
use App\Models\User;
use App\Exceptions\UserException;
use App\Services\AuditLogService;

class UserChangePasswordAction
{
    public function __construct(
        private LogService $logService,
        private AuditLogService $auditLogService,
    ) {}

    public function execute(ChangePasswordData $data, User $user)
    {
        try {
            if (!Hash::check($data->old_password, $user->password)) {
                throw UserException::oldPasswordIncorrect();
            }

            $user->password = Hash::make($data->new_password);
            $user->save();

            $this->auditLogService->auditLogChangePassword($user);

            return $user;
        } catch (\Throwable $e) {
            if ($e instanceof UserException) {
                throw $e;
            }
            $this->logService->logFailure($e);
            throw new HttpException(500, 'Internal server error');
        }
    }
}