<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Constants\ResponseMessage;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Actions\User\UserDeleteAccountAction;
use App\Http\Resources\UserResource;


class UserDeleteAccountController extends Controller
{
    use ApiResponse;

    public function __construct(
        private UserDeleteAccountAction $userDeleteAccountAction,
    ) {}

    public function __invoke(Request $request){

        $user = $this->userDeleteAccountAction->execute($request->user());

        return $this->success(
            message: ResponseMessage::ACCOUNT_DELETED_SUCCESSFULLY,
            data: UserResource::make($user),
            status: 200,
        );
    }
}
