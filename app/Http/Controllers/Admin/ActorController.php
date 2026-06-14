<?php

namespace App\Http\Controllers\Admin;

use App\Constants\ResponseMessage;
use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use App\Services\Admin\ActorService;
use App\Models\Actor;
use App\Http\Resources\ActorResource;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\Admin\Actor\{CreateActorRequest, UpdateActorRequest};

class ActorController extends Controller
{
    use ApiResponse;

    public function __construct(
        private ActorService $actorService,
    ) {}

    public function index(): JsonResponse
    {
        $actors = Actor::all();

        return $this->success(
            data: ActorResource::collection($actors),
            message: ResponseMessage::DATA_FETCHED_SUCCESSFULLY,
            status: 200,
        );
    }

    public function store(CreateActorRequest $request): JsonResponse
    {
        $actor = $this->actorService->createActor($request->validated());

        return $this->success(
            data: ActorResource::make($actor),
            message: ResponseMessage::DATA_CREATED_SUCCESSFULLY,
            status: 201,
        );
    }

    public function show(Actor $actor): JsonResponse
    {
        return $this->success(
            data: ActorResource::make($actor),
            message: ResponseMessage::DATA_FETCHED_SUCCESSFULLY,
            status: 200,
        );
    }

    public function update(UpdateActorRequest $request, Actor $actor): JsonResponse
    {
        $actor = $this->actorService->updateActor($request->validated(), $actor);

        return $this->success(
            data: ActorResource::make($actor),
            message: ResponseMessage::DATA_UPDATED_SUCCESSFULLY,
            status: 200,
        );
    }

    public function destroy(Actor $actor): JsonResponse
    {
        $this->actorService->deleteActor($actor);

        return $this->success(
            message: ResponseMessage::DATA_DELETED_SUCCESSFULLY,
            status: 200,
        );
    }
}
