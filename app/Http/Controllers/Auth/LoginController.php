<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Actions\Auth\LoginAction;
use App\Data\Auth\LoginData;
use App\Traits\ApiResponse;
use App\Constants\ResponseMessage;
use App\Http\Resources\UserResource;

class LoginController extends Controller
{
    use ApiResponse;
    public function __construct(
        private LoginAction $loginAction,
    ) {}

    public function __invoke(LoginData $data){
        $result = $this->loginAction->execute($data);

        return $this->success(
            data: ['user' => UserResource::make($result['user']), 'token' => $result['token']],
            message: ResponseMessage::USER_LOGIN_SUCCESSFULLY,
            status: 200,
        );
    }
}
