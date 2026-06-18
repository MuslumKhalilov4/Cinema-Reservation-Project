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
            $movie->cast()->attach($this->formatCast($actors));
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

        $oldPoster = $movie->poster_url;

        DB::beginTransaction();
        try {
            $movie->update($data);

            if ($genreIds !== null) {
                $movie->genres()->sync($genreIds);
            }

            if ($actors !== null) {
                $movie->cast()->sync($this->formatCast($actors));
            }

            if ($newPoster && $oldPoster) {
                $this->fileService->delete($oldPoster);
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
        $posterUrl = $movie->poster_url;

        $movie->delete();

        if ($posterUrl) {
            $this->fileService->delete($posterUrl);
        }
    }

    private function formatCast(array $actors): array
    {
        return collect($actors)->mapWithKeys(fn (array $actor) => [
            $actor['actor_id'] => ['character_name' => $actor['character_name']],
        ])->all();
    }
}
