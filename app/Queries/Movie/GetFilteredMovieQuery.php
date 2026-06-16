<?php

namespace App\Queries\Movie;

use App\Models\Movie;
use Illuminate\Pagination\LengthAwarePaginator;

class GetFilteredMovieQuery
{
    public function execute(array $data): LengthAwarePaginator
    {
        $query = Movie::query();

        $movies = $query->when($data['search'] ?? null, function ($query, $search) {
            $query->where('title->' . app()->getLocale(), 'like', '%' . $search . '%');
        })
        ->when($data['genre_id'] ?? null, function ($query, $genreId) {
            $query->whereHas('genres', function ($query) use ($genreId) {
                $query->where('genres.id', $genreId);
            });
        })
        ->when($data['status'] ?? null, function ($query, $status) {
            $query->where('status', $status);
        })
        ->when($data['sort'] ?? null, function ($query, $sort) {
            $query->orderBy($sort, $data['sort_direction'] ?? 'asc');
        })
        ->paginate($data['per_page'] ?? 10);

        return $movies;
    }
}