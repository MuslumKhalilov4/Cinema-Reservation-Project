<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Verified;
use App\Services\AuditLogService;

class LogVerifiedUser
{
    public function __construct(
        private AuditLogService $auditLogService,
    ) {}

    public function handle(Verified $event): void
    {
        $user = $event->user;
        $this->auditLogService->auditLogVerifyEmail($user);
        
    }
}
