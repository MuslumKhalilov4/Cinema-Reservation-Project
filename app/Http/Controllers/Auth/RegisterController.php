<?php

namespace App\Http\Controllers\Auth;

use App\Data\Auth\RegisterData;
use App\Http\Controllers\Controller;
use App\Actions\Auth\RegisterAction;
use App\Traits\ApiResponse;
use App\Constants\ResponseMessage;
use App\Http\Resources\UserResource;

class RegisterController extends Controller
{
    use ApiResponse;
    public function __construct(
        private RegisterAction $registerAction,
    ) {}

    public function __invoke(RegisterData $data){
        $result = $this->registerAction->execute($data);

        return $this->success(
            data: ['user' => UserResource::make($result['user']), 'token' => $result['token']],
            message: ResponseMessage::USER_REGISTERED_SUCCESSFULLY,
            status: 201,
        );
    }
}
