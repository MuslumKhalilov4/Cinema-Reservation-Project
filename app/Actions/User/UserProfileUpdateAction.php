<?php

namespace App\Actions\User;

use App\Data\User\UpdateProfileData;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Services\AuditLogService;
use App\Services\LogService;
use Symfony\Component\HttpKernel\Exception\HttpException;

class UserProfileUpdateAction
{
    public function __construct(
        private AuditLogService $auditLogService,
        private LogService $logService,
    ) {}

    public function execute(UpdateProfileData $data, User $user)
    {
        DB::beginTransaction();
        try {
            $email_changed = $data->email && $user->email !== $data->email;

            $user->fill($data->toArray());

            if ($email_changed) {
                $user->email_verified_at = null;
            }

            $auditLogData = $this->prepareAuditLogData($user);

            $user->save();
            DB::commit();

            if ($email_changed) {
                $user->sendEmailVerificationNotification();
            }

            $this->auditLogService->auditLogUpdateProfile($user, $auditLogData);

            return $user;
        } catch (\Throwable $e) {
            DB::rollBack();
            $this->logService->logFailure($e);
            throw new HttpException(500, 'Internal server error');
        }
    }   

    public function prepareAuditLogData(User $user): array
    {
        $newData = $user->getDirty();

        $oldData = [];
        foreach ($newData as $key => $value) {
            $oldData[$key] = $user->getOriginal($key);
        }

        return [
            'old_data' => $oldData,
            'new_data' => $newData,
        ];
    }
}