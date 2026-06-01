<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class LogService
{
    public function logFailure(string $contextMessage, \Throwable $e, array $additionalData = []): void
    {
        Log::error($contextMessage, array_merge([
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString(),
        ], $additionalData));
    }
}