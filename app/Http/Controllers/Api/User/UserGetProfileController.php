<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;
use App\Http\Resources\UserResource;
use App\Constants\ResponseMessage;

class UserGetProfileController extends Controller
{
    use ApiResponse;

    public function __invoke(Request $request){
        $user = $request->user();

        return $this->success(
            data: ['user' => UserResource::make($user)],
            message: ResponseMessage::DATA_FETCHED_SUCCESSFULLY,
            status: 200,
        );
    }
}
