<?php

namespace App\Services\Admin;

use App\Models\Movie;
use App\Services\FileService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;

class MovieService
{
    public function __construct(
        private FileService $fileService,
    ) {}

    public function createMovie(array $data): Movie
    {
        $data['poster_url'] = $this->fileService->upload($data['poster'], 'movies');
        unset($data['poster']);

        $actors = Arr::pull($data, 'actors');
        $genreIds = Arr::pull($data, 'genre_ids');

        DB::beginTransaction();
        try {
            $movie = Movie::create($data);
            $movie->genres()->attach($genreIds);
            $movie->cast()->attach($actors);
            DB::commit();
            return $movie->refresh();
        } catch (\Throwable $e) {
            DB::rollBack();
            $this->fileService->delete($data['poster_url']);
            throw $e;
        }
    }

    public function updateMovie(array $data, Movie $movie): Movie
    {
        $newPoster = null;
        if (isset($data['poster'])) {
            $newPoster = $this->fileService->upload($data['poster'], 'movies');
            $data['poster_url'] = $newPoster;
            unset($data['poster']);
        }

        $actors = Arr::pull($data, 'actors');
        $genreIds = Arr::pull($data, 'genre_ids');

        DB::beginTransaction();
        try {
            $movie->update($data);
            $movie->genres()->sync($genreIds);
            $movie->cast()->sync($actors);

            if ($newPoster) {
                $this->fileService->delete($movie->poster_url);
            }

            DB::commit();
            return $movie->refresh();
        } catch (\Throwable $e) {
            DB::rollBack();
            if ($newPoster) {
                $this->fileService->delete($newPoster);
            }
            throw $e;
        }
    }

    public function deleteMovie(Movie $movie): void
    {
        $movie->delete();
        if ($movie->poster_url) {
            $this->fileService->delete($movie->poster_url);
        }
    }
}
