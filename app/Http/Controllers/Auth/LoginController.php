<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Actions\Auth\LoginAction;
use App\Http\Requests\Auth\LoginRequest;
use App\Traits\ApiResponse;
use App\Constants\ResponseMessage;
use App\Http\Resources\UserResource;

class LoginController extends Controller
{
    use ApiResponse;
    public function __construct(
        private LoginAction $loginAction,
    ) {}

    public function __invoke(LoginRequest $request){
        $result = $this->loginAction->execute($request->validated());

        return $this->success(
            data: ['user' => UserResource::make($result['user']), 'token' => $result['token']],
            message: ResponseMessage::USER_LOGIN_SUCCESSFULLY,
            status: 200,
        );
    }
}
