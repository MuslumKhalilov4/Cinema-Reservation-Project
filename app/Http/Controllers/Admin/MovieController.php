<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Constants\ResponseMessage;
use App\Queries\Movie\GetFilteredMovieQuery;
use App\Traits\ApiResponse;
use App\Http\Requests\Admin\Movie\FilterMovieRequest;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\Movie\MovieListResource;


class MovieController extends Controller
{
    use ApiResponse;

    public function index(FilterMovieRequest $request, GetFilteredMovieQuery $getFilteredMovieQuery): JsonResponse
    {
        $movies = $getFilteredMovieQuery->execute($request->validated());

        return $this->paginated(
            resource: MovieListResource::collection($movies),
            message: ResponseMessage::DATA_FETCHED_SUCCESSFULLY,
            status: 200,
        );
    }
}
