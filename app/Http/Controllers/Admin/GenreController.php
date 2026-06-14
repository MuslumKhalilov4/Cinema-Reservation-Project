<?php

namespace App\Http\Controllers\Admin;

use App\Constants\ResponseMessage;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Genre\CreateGenreRequest;
use App\Http\Requests\Admin\Genre\UpdateGenreRequest;
use App\Http\Resources\GenreResource;
use App\Models\Genre;
use App\Traits\ApiResponse;
use Illuminate\Support\Str;

class GenreController extends Controller
{
    use ApiResponse;

    public function index()
    {
        $genres = Genre::all();

        return $this->success(
            data: GenreResource::collection($genres),
            message: ResponseMessage::DATA_FETCHED_SUCCESSFULLY,
            status: 200,
        );
    }

    public function store(CreateGenreRequest $request)
    {
        $name = $request->validated('name');

        $genre = Genre::create([
            'name' => $name,
            'slug' => Str::slug($name['en']),
        ]);

        return $this->success(
            data: GenreResource::make($genre),
            message: ResponseMessage::DATA_CREATED_SUCCESSFULLY,
            status: 201,
        );
    }

    public function show(Genre $genre)
    {
        return $this->success(
            data: GenreResource::make($genre),
            message: ResponseMessage::DATA_FETCHED_SUCCESSFULLY,
            status: 200,
        );
    }

    public function update(UpdateGenreRequest $request, Genre $genre)
    {
        if (array_key_exists('name', $request->validated())) {
            $name = array_filter($request->validated('name'));

            $genre->name = array_merge($genre->getTranslations('name'), $name);

            if (isset($name['en'])) {
                $genre->slug = Str::slug($name['en']);
            }
        }

        $genre->save();

        return $this->success(
            data: GenreResource::make($genre),
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
