<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Repositories\Interfaces\CommentRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class CommentService
{
    protected $commentRepository;

    public function __construct(CommentRepositoryInterface $commentRepository)
    {
        $this->commentRepository = $commentRepository;
    }

    public function getTicketComments($ticketId)
    {
        return $this->commentRepository->getTicketComments($ticketId);
    }

    public function createComment(array $data)
    {
        $data['user_id'] = Auth::id();
        $comment = $this->commentRepository->create($data);

        // Log the comment creation
        ActivityLog::create([
            'ticket_id' => $data['ticket_id'],
            'user_id' => Auth::id(),
            'action' => 'commented',
            'description' => 'Comment added',
            'new_values' => $data
        ]);

        return $comment;
    }

    public function updateComment($id, array $data)
    {
        $oldComment = $this->commentRepository->find($id);
        $comment = $this->commentRepository->update($id, $data);

        // Log the comment update
        ActivityLog::create([
            'ticket_id' => $comment->ticket_id,
            'user_id' => Auth::id(),
            'action' => 'updated_comment',
            'description' => 'Comment updated',
            'old_values' => $oldComment->toArray(),
            'new_values' => $data
        ]);

        return $comment;
    }

    public function deleteComment($id)
    {
        $comment = $this->commentRepository->find($id);

        // Log the comment deletion
        ActivityLog::create([
            'ticket_id' => $comment->ticket_id,
            'user_id' => Auth::id(),
            'action' => 'deleted_comment',
            'description' => 'Comment deleted',
            'old_values' => $comment->toArray()
        ]);

        return $this->commentRepository->delete($id);
    }
}
