<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Constants\ResponseMessage;

class VerifyEmailController extends Controller
{
    public function __invoke(int $id, string $hash)
    {
        $user = User::find($id);
        if (!$user || !hash_equals($hash, sha1($user->getEmailForVerification()))) {
            return response()->json([
                'message' => ResponseMessage::EMAIL_VERIFICATION_INVALID,
            ], 404);
        }
        if ($user->hasVerifiedEmail()) {
            return response()->json([
                'message' => ResponseMessage::EMAIL_VERIFICATION_ALREADY_VERIFIED,
            ], 400);
        }
        $user->markEmailAsVerified();
        return response()->json([
            'message' => ResponseMessage::EMAIL_VERIFICATION_SUCCESSFUL,
        ], 200);
    }
}
