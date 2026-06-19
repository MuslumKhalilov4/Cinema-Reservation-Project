<?php

namespace App\Http\Controllers\Admin;

use App\Constants\ResponseMessage;
use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use App\Services\Admin\CinemaService;
use App\Models\Cinema;
use App\Http\Resources\CinemaResource;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\Admin\Cinema\{CreateCinemaRequest, UpdateCinemaRequest};

class CinemaController extends Controller
{
    use ApiResponse;

    public function __construct(
        private CinemaService $cinemaService,
    ) {}

    public function index(): JsonResponse
    {
        $cinemas = Cinema::all();

        return $this->success(
            data: CinemaResource::collection($cinemas),
            message: ResponseMessage::DATA_FETCHED_SUCCESSFULLY,
            status: 200,
        );
    }

    public function store(CreateCinemaRequest $request): JsonResponse
    {
        $cinema = $this->cinemaService->createCinema($request->validated());

        return $this->success(
            data: CinemaResource::make($cinema),
            message: ResponseMessage::DATA_CREATED_SUCCESSFULLY,
            status: 201,
        );
    }

    public function show(Cinema $cinema): JsonResponse
    {
        return $this->success(
            data: CinemaResource::make($cinema),
            message: ResponseMessage::DATA_FETCHED_SUCCESSFULLY,
            status: 200,
        );
    }

    public function update(UpdateCinemaRequest $request, Cinema $cinema): JsonResponse
    {
        $cinema = $this->cinemaService->updateCinema($request->validated(), $cinema);

        return $this->success(
            data: CinemaResource::make($cinema),
            message: ResponseMessage::DATA_UPDATED_SUCCESSFULLY,
            status: 200,
        );
    }

    public function destroy(Cinema $cinema): JsonResponse
    {
        $this->cinemaService->deleteCinema($cinema);

        return $this->success(
            message: ResponseMessage::DATA_DELETED_SUCCESSFULLY,
            status: 200,
        );
    }
}
