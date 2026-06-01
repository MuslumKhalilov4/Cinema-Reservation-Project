<?php

namespace App\Exceptions;

use Exception;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class AuthException extends Exception
{
    protected int $statusCode;
   
    public function __construct(string $message, int $statusCode = 401){
        parent::__construct($message);
        $this->statusCode = $statusCode;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public static function invalidCredentials(): self
    {
        return new self(message: 'Invalid credentials', statusCode: 401);
    }

    public static function unexpecedAuthException(): self
    {
        return new self(message: 'Unexpected authentication exception occurred. Please try again later.', statusCode: 500);
    }

    public function render(): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $this->getMessage(),
        ], $this->getStatusCode());
    }
}
