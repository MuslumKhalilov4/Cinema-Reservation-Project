<?php

namespace App\Services\Admin;

use App\Models\Cinema;
use App\Services\FileService;

class CinemaService
{
    public function __construct(
        private FileService $fileService,
    ) {}

    public function createCinema(array $data): Cinema
    {
        $data['image_url'] = $this->fileService->upload($data['image'], 'cinemas');
        unset($data['image']);

        $cinema = Cinema::create($data);
        return $cinema;
    }

    public function updateCinema(array $data, Cinema $cinema): Cinema
    {
        if (isset($data['image'])) {
            if ($cinema->image_url) {
                $this->fileService->delete($cinema->image_url);
            }
            $data['image_url'] = $this->fileService->upload($data['image'], 'cinemas');
        }

        unset($data['image']);

        $cinema->update($data);
        return $cinema;
    }

    public function deleteCinema(Cinema $cinema): void
    {
        if ($cinema->image_url) {
            $this->fileService->delete($cinema->image_url);
        }
        $cinema->delete();
    }
}
