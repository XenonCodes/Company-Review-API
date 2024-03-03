<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\Services\Api\UserService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Получить список всех пользователей.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            $users = $this->userService->getAllUsers();
            return response()->json(['data' => UserResource::collection($users)], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Не удалось получить список пользователей'], 500);
        }
    }

    /**
     * Получить информацию о студенте.
     *
     * @param int $userId
     * @return JsonResponse
     */
    public function show(int $userId): JsonResponse
    {
        try {
            $user = $this->userService->getUserInfo($userId);
            return response()->json(['data' => new UserResource($user)], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Пользователь не найден'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Не удалось получить информацию о пользователе'], 500);
        }
    }

    /**
     * Создать нового пользователя.
     *
     * @param UserCreateRequest $request
     * @return JsonResponse
     */
    public function store(UserCreateRequest $request): JsonResponse
    {
        try {
            $user = $this->userService->createUser($request->validated(), $request->file('avatar'));
            return response()->json(['data' => new UserResource($user)], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Не удалось создать нового пользователя'], 500);
        }
    }

    /**
     * Обновить информацию о пользователе.
     *
     * @param UserUpdateRequest $request
     * @param int $userId Идентификатор пользователя.
     * @return JsonResponse
     */
    public function update(UserUpdateRequest $request, int $userId): JsonResponse
    {
        try {
            $user = $this->userService->updateUser($userId, $request->validated(), $request->file('avatar'));
            return response()->json(['data' => new UserResource($user)], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Пользователь не найден'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Не удалось обновить информацию о пользователе'], 500);
        }
    }

    /**
     * Удалить пользователя.
     *
     * @param int $userId Идентификатор пользователя.
     * @return JsonResponse
     */
    public function destroy(int $userId): JsonResponse
    {
        try {
            $this->userService->deleteUser($userId);
            return response()->json(['message' => 'Пользователь успешно удален'], 204);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Пользователь не найден'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Не удалось удалить пользователя'], 500);
        }
    }
}
