<?php

namespace App\Services;

use App\Models\User;

class AuditLogService
{
    public function execute(User $user, string $event, string $description, array $properties = []): void
    {
        activity()
            ->useLog('auth')
            ->event($event)
            ->performedOn($user)
            ->causedBy($user)
            ->withProperties($properties)
            ->log($description);
    }

    public function auditLogLogout(User $user): void
    {
        $this->execute($user, 'logout', 'User logged out. Username: ' . $user->username);   
    }

    public function auditLogRegister(User $user): void
    {
        $this->execute($user, 'register', 'New user registered. Username: ' . $user->username, [
            'username' => $user->username,
            'email' => $user->email,
            'phone' => $user->phone,
        ]);
    }

    public function auditLogLogin(User $user): void
    {
        $this->execute($user, 'login', 'User logged in. Username: ' . $user->username);
    }

    public function auditLogVerifyEmail(User $user): void
    {
        $this->execute($user, 'verify_email', 'User verified email. Username: ' . $user->username);
    }
}