<?php

namespace App\Services\Admin;

use App\Models\Actor;
use App\Services\FileService;

class ActorService
{
    public function __construct(
        private FileService $fileService,
    ) {}

    public function createActor(array $data): Actor
    {
        $data['picture_url'] = $this->fileService->upload($data['picture'], 'actors');
        unset($data['picture']);

        $actor = Actor::create($data);
        return $actor;
    }

    public function updateActor(array $data, Actor $actor): Actor
    {
        if (isset($data['picture'])) {
            if ($actor->picture_url) {
                $this->fileService->delete($actor->picture_url);
            }
            $data['picture_url'] = $this->fileService->upload($data['picture'], 'actors');
        }

        unset($data['picture']);

        $actor->update($data);
        return $actor;
    }

    public function deleteActor(Actor $actor): void
    {
        if ($actor->picture_url) {
            $this->fileService->delete($actor->picture_url);
        }
        $actor->delete();
    }
}