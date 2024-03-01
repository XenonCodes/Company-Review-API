<?php

namespace App\Services\Api;

use App\Models\Comment;

class CommentService
{
    /**
     * Создать новый комментарий.
     *
     * @param array $commentData Данные комментария для создания.
     * @return Comment
     */
    public function createComment(array $commentData): Comment
    {
        return Comment::create($commentData);
    }

    /**
     * Обновить информацию о комментарии.
     *
     * @param int $commentId Идентификатор комментария.
     * @param array $commentData Данные комментария для обновления.
     * @return Comment
     */
    public function updateComment(int $commentId, array $commentData): Comment
    {
        $comment = Comment::findOrFail($commentId);
        $comment->update($commentData);
        
        return $comment;
    }

    /**
     * Удалить комментарий.
     *
     * @param int $commentId Идентификатор комментария.
     * @return void
     */
    public function deleteComment(int $commentId): void
    {
        $comment = Comment::findOrFail($commentId);
        $comment->delete();
    }
}
