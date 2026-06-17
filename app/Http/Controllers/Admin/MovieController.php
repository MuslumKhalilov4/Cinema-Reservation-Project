<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Constants\ResponseMessage;
use App\Queries\Movie\GetFilteredMovieQuery;
use App\Traits\ApiResponse;
use App\Http\Requests\Admin\Movie\{CreateMovieRequest, FilterMovieRequest, UpdateMovieRequest};
use Illuminate\Http\JsonResponse;
use App\Http\Resources\Movie\{MovieListResource, MovieDetailResource};
use App\Services\Admin\MovieService;
use App\Models\Movie;

class MovieController extends Controller
{
    use ApiResponse;

    public function __construct(
        private MovieService $movieService,
    ) {}

    public function index(FilterMovieRequest $request, GetFilteredMovieQuery $getFilteredMovieQuery): JsonResponse
    {
        $movies = $getFilteredMovieQuery->execute($request->validated());

        return $this->paginated(
            resource: MovieListResource::collection($movies),
            message: ResponseMessage::DATA_FETCHED_SUCCESSFULLY,
            status: 200,
        );
    }

    public function store(CreateMovieRequest $request): JsonResponse
    {
        $movie = $this->movieService->createMovie($request->validated());

        return $this->success(
            data: MovieDetailResource::make($movie),
            message: ResponseMessage::DATA_CREATED_SUCCESSFULLY,
            status: 201,
        );
    }

    public function show(Movie $movie): JsonResponse
    {
        return $this->success(
            data: MovieDetailResource::make($movie),
            message: ResponseMessage::DATA_FETCHED_SUCCESSFULLY,
            status: 200,
        );
    }

    public function update(UpdateMovieRequest $request, Movie $movie): JsonResponse
    {
        $movie = $this->movieService->updateMovie($request->validated(), $movie);

        return $this->success(
            data: MovieDetailResource::make($movie),
            message: ResponseMessage::DATA_UPDATED_SUCCESSFULLY,
            status: 200,
        );
    }

    public function destroy(Movie $movie): JsonResponse
    {
        $this->movieService->deleteMovie($movie);

        return $this->success(
            message: ResponseMessage::DATA_DELETED_SUCCESSFULLY,
            status: 200,
        );
    }
}
