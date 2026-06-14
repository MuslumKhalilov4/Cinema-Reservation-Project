<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use App\Http\Requests\User\ChangePasswordRequest;
use App\Actions\User\UserChangePasswordAction;  
use Illuminate\Support\Facades\Auth;
use App\Constants\ResponseMessage;

class UserChangePasswordController extends Controller
{
    use ApiResponse;
    public function __construct(
        private UserChangePasswordAction $userChangePasswordAction,
    ) {}

    public function __invoke(ChangePasswordRequest $request)
    {
        $user = Auth::user();

        $user = $this->userChangePasswordAction->execute($request->validated(), $user);

        return $this->success(
            message: ResponseMessage::PASSWORD_CHANGED_SUCCESSFULLY,
            status: 200,
        );
    }
}
