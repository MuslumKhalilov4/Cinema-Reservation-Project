<?php

namespace App\Http\Controllers\Admin;

use App\Constants\ResponseMessage;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SeatCategory\{CreateSeatCategoryRequest, UpdateSeatCategoryRequest};
use App\Http\Resources\SeatCategoryResource;
use App\Models\SeatCategory;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class SeatCategoryController extends Controller
{
    use ApiResponse;

    public function index(): JsonResponse
    {
        $seatCategories = SeatCategory::all();

        return $this->success(
            data: SeatCategoryResource::collection($seatCategories),
            message: ResponseMessage::DATA_FETCHED_SUCCESSFULLY,
            status: 200,
        );
    }

    public function store(CreateSeatCategoryRequest $request): JsonResponse
    {
        $seatCategory = SeatCategory::create($request->validated());

        return $this->success(
            data: SeatCategoryResource::make($seatCategory),
            message: ResponseMessage::DATA_CREATED_SUCCESSFULLY,
            status: 201,
        );
    }

    public function show(SeatCategory $seatCategory): JsonResponse
    {
        return $this->success(
            data: SeatCategoryResource::make($seatCategory),
            message: ResponseMessage::DATA_FETCHED_SUCCESSFULLY,
            status: 200,
        );
    }

    public function update(UpdateSeatCategoryRequest $request, SeatCategory $seatCategory): JsonResponse
    {
        $seatCategory->update($request->validated());

        return $this->success(
            data: SeatCategoryResource::make($seatCategory),
            message: ResponseMessage::DATA_UPDATED_SUCCESSFULLY,
            status: 200,
        );
    }

    public function destroy(SeatCategory $seatCategory): JsonResponse
    {
        $seatCategory->delete();

        return $this->success(
            message: ResponseMessage::DATA_DELETED_SUCCESSFULLY,
            status: 200,
        );
    }
}
