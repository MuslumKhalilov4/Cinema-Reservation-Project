<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class LogService
{
    public function logFailure(\Throwable $e, ?array $additionalData = null, string $contextMessage = 'Internal server error'): void
    {
        Log::channel('errors')->error($contextMessage, array_merge([
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString(),
        ], $additionalData ?? []));
    }
}