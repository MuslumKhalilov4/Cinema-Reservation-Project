<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;
use App\Constants\ResponseMessage;
use App\Services\AuditLogService;
use App\Models\User;

class LogoutController extends Controller
{
    use ApiResponse;

    public function __construct(
        private AuditLogService $auditLogService,
    ) {}

    public function __invoke(Request $request){
        /** @var User $user */
        $request->user()->currentAccessToken()->delete();

        $this->auditLogService->auditLogLogout($request->user());

        return $this->success(
            message: ResponseMessage::USER_LOGOUT_SUCCESSFULLY,
            status: 200,
        );
    }
}
