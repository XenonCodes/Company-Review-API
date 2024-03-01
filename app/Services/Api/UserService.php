<?php

namespace App\Services\Api;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class UserService
{
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
     * @return User
     */
    public function createUser(array $userData): User
    {
        return User::create($userData);
    }

    /**
     * Обновить информацию о пользователе.
     *
     * @param int $userId Идентификатор пользователя.
     * @param array $userData Данные пользователя для обновления.
     * @return User
     */
    public function updateUser(int $userId, array $userData): User
    {
        $user = User::findOrFail($userId);
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
        $user->delete();
    }
}
