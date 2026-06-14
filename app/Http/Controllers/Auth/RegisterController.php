<?php

namespace App\Http\Controllers\Auth;

use App\Dto\Auth\RegisterDto;
use App\Http\Controllers\Controller;
use App\Actions\Auth\RegisterAction;
use App\Http\Requests\Auth\RegisterRequest;
use App\Traits\ApiResponse;
use App\Constants\ResponseMessage;
use App\Http\Resources\UserResource;

class RegisterController extends Controller
{
    use ApiResponse;
    public function __construct(
        private RegisterAction $registerAction,
    ) {}

    public function __invoke(RegisterRequest $request){
        $result = $this->registerAction->execute(RegisterDto::fromRequest($request));

        return $this->success(
            data: ['user' => UserResource::make($result['user']), 'token' => $result['token']],
            message: ResponseMessage::USER_REGISTERED_SUCCESSFULLY,
            status: 201,
        );
    }
}
