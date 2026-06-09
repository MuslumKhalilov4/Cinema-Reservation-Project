<?php

namespace App\Actions\User;

use App\Models\User;
use App\Services\FileService;
use App\Services\AuditLogService;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\HttpException;
use App\Services\LogService;

class UserDeleteAccountAction
{
    public function __construct(
        private FileService $fileService,
        private AuditLogService $auditLogService,
        private LogService $logService,
    ) {}

    public function execute(User $user): User
    {
        DB::beginTransaction();
        try {
            if ($user->avatar_url) {
                $this->fileService->delete($user->avatar_url);
            }

            $user->delete();
            $this->auditLogService->auditLogDeleteAccount($user);

            DB::commit();

            return $user;
        } catch (\Throwable $e) {
            DB::rollBack();
            $this->logService->logFailure($e);
            throw new HttpException(500, 'Internal server error');
        }
    }
}
