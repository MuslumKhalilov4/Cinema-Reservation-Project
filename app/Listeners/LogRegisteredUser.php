<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Auth\Events\Registered;
use App\Services\AuditLogService;

class LogRegisteredUser
{
    public function __construct(
        private AuditLogService $auditLogService,
    ) {}

    public function handle(Registered $event): void
    {
        $user = $event->user;
        $this->auditLogService->auditLogRegister($user);
    }
}
