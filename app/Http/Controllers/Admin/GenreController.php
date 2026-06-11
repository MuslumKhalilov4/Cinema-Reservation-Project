<?php

namespace App\Http\Controllers\Admin;

use App\Constants\ResponseMessage;
use App\Data\Admin\Genre\CreateGenreData;
use App\Data\Admin\Genre\UpdateGenreData;
use App\Http\Controllers\Controller;
use App\Models\Genre;
use App\Traits\ApiResponse;
use Illuminate\Support\Str;
use Spatie\LaravelData\Optional;
use App\Data\Admin\Genre\GenreData;

class GenreController extends Controller
{
    use ApiResponse;

    public function index()
    {
        $genres = Genre::all();

        return $this->success(
            data: GenreData::collect($genres),
            message: ResponseMessage::DATA_FETCHED_SUCCESSFULLY,
            status: 200,
        );
    }

    public function store(CreateGenreData $data)
    {
        $genre = Genre::create([
            'name' => $data->name,
            'slug' => Str::slug($data->name['en']),
        ]);

        return $this->success(
            data: GenreData::from($genre),
            message: ResponseMessage::DATA_CREATED_SUCCESSFULLY,
            status: 201,
        );
    }

    public function show(Genre $genre)
    {
        return $this->success(
            data: GenreData::from($genre),
            message: ResponseMessage::DATA_FETCHED_SUCCESSFULLY,
            status: 200,
        );
    }

    public function update(UpdateGenreData $data, Genre $genre)
    {
        if (! $data->name instanceof Optional) {
            $genre->name = array_merge($genre->getTranslations('name'), array_filter($data->name));

            if (isset($data->name['en'])) {
                $genre->slug = Str::slug($data->name['en']);
            }
        }

        $genre->save();

        return $this->success(
            data: GenreData::from($genre),
            message: ResponseMessage::DATA_UPDATED_SUCCESSFULLY,
            status: 200,
        );
    }

    public function destroy(Genre $genre)
    {
        $genre->delete();

        return $this->success(
            message: ResponseMessage::DATA_DELETED_SUCCESSFULLY,
            status: 200,
        );
    }
}
