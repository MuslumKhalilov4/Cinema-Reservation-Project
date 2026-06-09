<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

class UserException extends Exception
{
    public function __construct(string $message, int $statusCode = 500)
    {
        parent::__construct($message, $statusCode);
    }

    public static function userNotFound(): self
    {
        return new self(message: 'User not found', statusCode: 404);
    }

    public static function oldPasswordIncorrect(): self
    {
        return new self(message: 'Old password is incorrect', statusCode: 400);
    }

    public function render(): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $this->getMessage(),
        ], $this->getCode());
    }
}
