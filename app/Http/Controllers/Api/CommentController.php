<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CommentCreateRequest;
use App\Http\Requests\CommentUpdateRequest;
use App\Services\Api\CommentService;
use Illuminate\Http\JsonResponse;

class CommentController extends Controller
{
    protected $commentService;

    public function __construct(CommentService $commentService)
    {
        $this->commentService = $commentService;
    }

    /**
     * Создать новый комментарий.
     *
     * @param CommentRequest $request
     * @return JsonResponse
     */
    public function store(CommentCreateRequest $request): JsonResponse
    {
        $comment = $this->commentService->createComment($request->validated());

        return response()->json($comment, 201);
    }

    /**
     * Обновить информацию о комментарии.
     *
     * @param CommentRequest $request
     * @param int $id Идентификатор комментария.
     * @return JsonResponse
     */
    public function update(CommentUpdateRequest $request, int $id): JsonResponse
    {
        $comment = $this->commentService->updateComment($id, $request->validated());

        return response()->json($comment, 200);
    }

    /**
     * Удалить комментарий.
     *
     * @param int $id Идентификатор комментария.
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $this->commentService->deleteComment($id);

        return response()->json(['message' => 'Comment deleted successfully'], 204);
    }
}
