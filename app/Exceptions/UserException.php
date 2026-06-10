<?php

namespace App\Exceptions;

class UserException extends AppException
{
    public static function userNotFound(): self
    {
        return new self(message: 'User not found', statusCode: 404);
    }

    public static function oldPasswordIncorrect(): self
    {
        return new self(message: 'Old password is incorrect', statusCode: 400);
    }
}
