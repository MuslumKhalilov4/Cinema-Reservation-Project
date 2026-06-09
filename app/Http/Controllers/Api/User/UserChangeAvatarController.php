<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use App\Data\User\ChangeAvatarData;
use App\Services\FileService;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Constants\ResponseMessage;

class UserChangeAvatarController extends Controller
{
    use ApiResponse;

    public function __construct(
        private FileService $fileService,
    ) {}

    public function __invoke(ChangeAvatarData $data)
    {
        /** @var User $user */
        $user = Auth::user();

        if ($data->avatar && $user->avatar_url) {
            $this->fileService->delete($user->avatar_url);
            $user->avatar_url = $this->fileService->upload($data->avatar, 'avatars');
            $user->save();
        }

        return $this->success(
            message: ResponseMessage::AVATAR_CHANGED_SUCCESSFULLY,
            status: 200,
        );
    }
}
