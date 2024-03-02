<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\Services\Api\UserService;
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
        $users = $this->userService->getAllUsers();
        return response()->json(['data' =>UserResource::collection($users)], 200);
    }

    /**
     * Получить информацию о студенте.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $user = $this->userService->getUserInfo($id);
        return response()->json(['data' => new UserResource($user)], 200);
    }

    /**
     * Создать нового пользователя.
     *
     * @param UserCreateRequest $request
     * @return JsonResponse
     */
    public function store(UserCreateRequest $request): JsonResponse
    {
        $user = $this->userService->createUser($request->validated(), $request->file('avatar'));

        return response()->json(['data' => new UserResource($user)], 201);
    }

    /**
     * Обновить информацию о пользователе.
     *
     * @param UserUpdateRequest $request
     * @param int $id Идентификатор пользователя.
     * @return JsonResponse
     */
    public function update(UserUpdateRequest $request, int $id): JsonResponse
    {
        $user = $this->userService->updateUser($id, $request->validated(), $request->file('avatar'));

        return response()->json(['data' => new UserResource($user)], 200);
    }

    /**
     * Удалить пользователя.
     *
     * @param int $id Идентификатор пользователя.
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $this->userService->deleteUser($id);
        return response()->json(['message' => 'User deleted successfully'], 204);
    }
}
