<?php

namespace App\Http\Controllers\Admin;

use App\Constants\ResponseMessage;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Hall\{CreateHallRequest, UpdateHallRequest};
use App\Http\Resources\HallResource;
use App\Models\Hall;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class HallController extends Controller
{
    use ApiResponse;

    public function index(): JsonResponse
    {
        $halls = Hall::all();

        return $this->success(
            data: HallResource::collection($halls),
            message: ResponseMessage::DATA_FETCHED_SUCCESSFULLY,
            status: 200,
        );
    }

    public function store(CreateHallRequest $request): JsonResponse
    {
        $hall = Hall::create($request->validated());

        return $this->success(
            data: HallResource::make($hall),
            message: ResponseMessage::DATA_CREATED_SUCCESSFULLY,
            status: 201,
        );
    }

    public function show(Hall $hall): JsonResponse
    {
        return $this->success(
            data: HallResource::make($hall),
            message: ResponseMessage::DATA_FETCHED_SUCCESSFULLY,
            status: 200,
        );
    }

    public function update(UpdateHallRequest $request, Hall $hall): JsonResponse
    {
        $hall->update($request->validated());

        return $this->success(
            data: HallResource::make($hall),
            message: ResponseMessage::DATA_UPDATED_SUCCESSFULLY,
            status: 200,
        );
    }

    public function destroy(Hall $hall): JsonResponse
    {
        $hall->delete();

        return $this->success(
            message: ResponseMessage::DATA_DELETED_SUCCESSFULLY,
            status: 200,
        );
    }
}
