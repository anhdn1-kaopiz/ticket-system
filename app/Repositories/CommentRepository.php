<?php

namespace App\Repositories;

use App\Models\Comment;
use App\Repositories\Interfaces\CommentRepositoryInterface;

class CommentRepository implements CommentRepositoryInterface
{
    protected $model;

    public function __construct(Comment $model)
    {
        $this->model = $model;
    }

    public function getTicketComments($ticketId)
    {
        return $this->model->where('ticket_id', $ticketId)
            ->with(['user'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update($id, array $data)
    {
        $comment = $this->find($id);
        $comment->update($data);
        return $comment;
    }

    public function delete($id)
    {
        return $this->model->destroy($id);
    }

    public function find($id)
    {
        return $this->model->findOrFail($id);
    }
}
