<?php

namespace App\Exceptions;

class AuthException extends AppException
{
    public static function invalidCredentials(): self
    {
        return new self(message: 'Invalid credentials', statusCode: 401);
    }

    public static function unexpecedAuthException(): self
    {
        return new self(message: 'Unexpected authentication exception occurred. Please try again later.', statusCode: 500);
    }
}
