<?php

namespace App\Services\Api;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ImageUploadService
{
    /**
     * Загружает изображение и возвращает путь к нему.
     *
     * @param UploadedFile $image
     * @param string $folder
     * @return string|null
     */
    public function uploadImage(UploadedFile $image, string $folder): ?string
    {
        if (!$image->isValid()) {
            return null; // Изображение не прошло проверку
        }

        $path = $image->store($folder);

        return $path;
    }

    /**
     * Удаляет изображение по указанному пути.
     *
     * @param string $path
     * @return bool
     */
    public function deleteImage(string $path): bool
    {
        if (Storage::exists($path)) {
            Storage::delete($path);
            return true;
        }

        return false;
    }
}
