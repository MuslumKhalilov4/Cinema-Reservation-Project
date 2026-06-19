<?php

namespace App\Http\Controllers\Admin;

use App\Constants\ResponseMessage;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Seat\{CreateSeatRequest, UpdateSeatRequest};
use App\Http\Resources\SeatResource;
use App\Models\Seat;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class SeatController extends Controller
{
    use ApiResponse;

    public function index(): JsonResponse
    {
        $seats = Seat::all();

        return $this->success(
            data: SeatResource::collection($seats),
            message: ResponseMessage::DATA_FETCHED_SUCCESSFULLY,
            status: 200,
        );
    }

    public function store(CreateSeatRequest $request): JsonResponse
    {
        $seat = Seat::create($request->validated());

        return $this->success(
            data: SeatResource::make($seat),
            message: ResponseMessage::DATA_CREATED_SUCCESSFULLY,
            status: 201,
        );
    }

    public function show(Seat $seat): JsonResponse
    {
        return $this->success(
            data: SeatResource::make($seat),
            message: ResponseMessage::DATA_FETCHED_SUCCESSFULLY,
            status: 200,
        );
    }

    public function update(UpdateSeatRequest $request, Seat $seat): JsonResponse
    {
        $seat->update($request->validated());

        return $this->success(
            data: SeatResource::make($seat),
            message: ResponseMessage::DATA_UPDATED_SUCCESSFULLY,
            status: 200,
        );
    }

    public function destroy(Seat $seat): JsonResponse
    {
        $seat->delete();

        return $this->success(
            message: ResponseMessage::DATA_DELETED_SUCCESSFULLY,
            status: 200,
        );
    }
}
