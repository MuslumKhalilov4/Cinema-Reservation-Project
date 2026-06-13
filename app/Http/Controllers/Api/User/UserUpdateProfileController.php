<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use App\Dto\User\UpdateProfileDto;
use App\Http\Requests\User\UpdateProfileRequest;
use App\Http\Resources\UserResource;
use App\Constants\ResponseMessage;
use Illuminate\Support\Facades\Auth;
use App\Actions\User\UserProfileUpdateAction;

class UserUpdateProfileController extends Controller
{
    use ApiResponse;

    public function __construct(
        private UserProfileUpdateAction $userProfileUpdateAction,
    ) {}

    public function __invoke(UpdateProfileRequest $request)
    {
        $user = Auth::user();

        $user = $this->userProfileUpdateAction->execute(UpdateProfileDto::fromRequest($request), $user);

        return $this->success(
            data: ['user' => UserResource::make($user)],
            message: ResponseMessage::DATA_UPDATED_SUCCESSFULLY,
            status: 200,
        );
    }
}
