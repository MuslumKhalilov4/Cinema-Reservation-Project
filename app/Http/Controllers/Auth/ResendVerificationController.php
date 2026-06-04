<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Constants\ResponseMessage;

class ResendVerificationController extends Controller
{
    public function __invoke(Request $request)
    {
        $request->user()->sendEmailVerificationNotification();
        return response()->json([
            'message' => ResponseMessage::EMAIL_VERIFICATION_LINK_SENT,
        ], 200);
    }
}
