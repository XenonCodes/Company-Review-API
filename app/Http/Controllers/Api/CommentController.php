<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CommentCreateRequest;
use App\Http\Requests\CommentUpdateRequest;
use App\Http\Resources\CommentResource;
use App\Services\Api\CommentService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;

class CommentController extends Controller
{
    protected $commentService;

    public function __construct(CommentService $commentService)
    {
        $this->commentService = $commentService;
    }

    /**
     * Получить список всех комментариев.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            $comments = $this->commentService->getAllComments();
            return response()->json(['data' => CommentResource::collection($comments)], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Не удалось получить список комментариев'], 500);
        }
    }

    /**
     * Получить комментарий.
     *
     * @param int $commentId
     * @return JsonResponse
     */
    public function show(int $commentId): JsonResponse
    {
        try {
            $comment = $this->commentService->getCommentInfo($commentId);
            return response()->json(['data' => new CommentResource($comment)], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Комментарий не найден'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Не удалось получить комментарий'], 500);
        }
    }

    /**
     * Создать новый комментарий.
     *
     * @param CommentCreateRequest $request
     * @return JsonResponse
     */
    public function store(CommentCreateRequest $request): JsonResponse
    {
        try {
            $comment = $this->commentService->createComment($request->validated());
            return response()->json(new CommentResource($comment), 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Не удалось создать комментарий'], 500);
        }
    }

    /**
     * Обновить информацию о комментарии.
     *
     * @param CommentUpdateRequest $request
     * @param int $commentId Идентификатор комментария.
     * @return JsonResponse
     */
    public function update(CommentUpdateRequest $request, int $commentId): JsonResponse
    {
        $comment = $this->commentService->updateComment($commentId, $request->validated());

        return response()->json($comment, 200);
        try {
            $comment = $this->commentService->updateComment($commentId, $request->validated());
            return response()->json($comment, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Комментарий не найден'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Не удалось обновить комментарий'], 500);
        }
    }

    /**
     * Удалить комментарий.
     *
     * @param int $commentId Идентификатор комментария.
     * @return JsonResponse
     */
    public function destroy(int $commentId): JsonResponse
    {
        try {
            $this->commentService->deleteComment($commentId);
            return response()->json(['message' => 'Комментарий успешно удален'], 204);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Комментарий не найден'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Не удалось удалить комментарий'], 500);
        }
    }
}
