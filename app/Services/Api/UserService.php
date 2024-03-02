<?php

namespace App\Services\Api;

use App\Models\User;
use App\Services\Api\ImageUploadService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;

class UserService
{
    protected $imageUploadService;

    public function __construct(ImageUploadService $imageUploadService)
    {
        $this->imageUploadService = $imageUploadService;
    }

    /**
     * Получить список всех пользователей.
     *
     * @return Collection
     */
    public function getAllUsers(): Collection
    {
        return User::all();
    }

    /**
     * Получить информацию о конкретном пользователе.
     *
     * @param int $userId Идентификатор пользователя.
     * @return User
     */
    public function getUserInfo(int $userId): User
    {
        $user = User::findOrFail($userId);

        return $user;
    }

    /**
     * Создать нового пользователя.
     *
     * @param array $userData Данные пользователя для создания.
     * @param ?UploadedFile $avatar Файл аватара пользователя.
     * @return User
     */
    public function createUser(array $userData, ?UploadedFile $avatar): User
    {
        if ($avatar) {
            // Загрузка и сохранение аватара пользователя
            $avatarPath = $this->imageUploadService->uploadImage($avatar, 'avatars');
            $userData['avatar'] = $avatarPath; // Сохраняем путь в модели пользователя
        }

        return User::create($userData);
    }

    /**
     * Обновить информацию о пользователе.
     *
     * @param int $userId Идентификатор пользователя.
     * @param array $userData Данные пользователя для обновления.
     * @param ?UploadedFile $newAvatar Новый файл аватара пользователя.
     * @return User
     */
    public function updateUser(int $userId, array $userData, ?UploadedFile $newAvatar): User
    {
        $user = User::findOrFail($userId);
        
        if ($newAvatar) {
            // Загрузка и сохранение новой картинки
            $avatarPath = $this->imageUploadService->uploadImage($newAvatar, 'avatars');
            $userData['avatar'] = $avatarPath; // Сохраняем путь в модели пользователя
            // Удаление старой картинки, если она существует
            if ($user->avatar) {
                $this->imageUploadService->deleteImage($user->avatar); // Путь к старой картинке
            }
            // Сохранение пути к новой картинке в модели пользователя
            $userData['avatar'] = $avatarPath;
        }

        $user->update($userData);

        return $user;
    }

    /**
     * Удалить пользователя.
     *
     * @param int $userId Идентификатор пользователя.
     * @return void
     */
    public function deleteUser(int $userId): void
    {
        $user = User::findOrFail($userId);

        // Удаление старой картинки, если она существует
        if ($user->avatar) {
            $this->imageUploadService->deleteImage($user->avatar); // Путь к старой картинке
        }

        $user->delete();
    }
}
