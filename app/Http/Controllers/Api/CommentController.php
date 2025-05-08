<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Ticket;
use App\Services\CommentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    protected $commentService;

    public function __construct(CommentService $commentService)
    {
        $this->commentService = $commentService;
    }

    public function index(Ticket $ticket): JsonResponse
    {
        $comments = $this->commentService->getTicketComments($ticket->id);
        return response()->json($comments);
    }

    public function store(Request $request, Ticket $ticket): JsonResponse
    {
        $validated = $request->validate([
            'comment_text' => 'required|string|max:1000'
        ]);

        $validated['ticket_id'] = $ticket->id;
        $comment = $this->commentService->createComment($validated);

        return response()->json($comment, 201);
    }

    public function update(Request $request, Comment $comment): JsonResponse
    {
        $validated = $request->validate([
            'comment_text' => 'required|string|max:1000'
        ]);

        $comment = $this->commentService->updateComment($comment->id, $validated);
        return response()->json($comment);
    }

    public function destroy(Comment $comment): JsonResponse
    {
        $this->commentService->deleteComment($comment->id);
        return response()->json(null, 204);
    }
}
