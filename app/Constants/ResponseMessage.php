<?php

namespace App\Constants;

class ResponseMessage
{
    public const USER_REGISTERED_SUCCESSFULLY = 'Registration completed successfully. Please check your email for verification.';
    public const USER_LOGIN_SUCCESSFULLY = 'Successfully logged in';
    public const USER_LOGOUT_SUCCESSFULLY = 'Successfully logged out';
    public const EMAIL_VERIFICATION_LINK_SENT = 'Verification link sent to your email address';
    public const EMAIL_VERIFICATION_SUCCESSFUL = 'Email verified successfully';
    public const EMAIL_VERIFICATION_INVALID = 'Invalid verification link';
    public const EMAIL_VERIFICATION_ALREADY_VERIFIED = 'Email already verified';
    public const PASSWORD_CHANGED_SUCCESSFULLY = 'Password changed successfully';
    public const AVATAR_CHANGED_SUCCESSFULLY = 'Avatar changed successfully';

    // General Responses
    public const DATA_FETCHED_SUCCESSFULLY = 'Data fetched successfully';
    public const DATA_UPDATED_SUCCESSFULLY = 'Data updated successfully';
}