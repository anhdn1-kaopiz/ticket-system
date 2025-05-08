<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Label;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Services\TicketService;

class TicketController extends Controller
{
    use AuthorizesRequests;

    protected $ticketService;

    public function __construct(TicketService $ticketService)
    {
        $this->ticketService = $ticketService;
    }

    public function index(Request $request): JsonResponse
    {
        $user = Auth::user();
        $filters = $request->only(['status', 'priority', 'category_id']);

        $tickets = $this->ticketService->getFilteredTickets($filters, $user);

        return response()->json($tickets);
    }

    public function store(Request $request): JsonResponse
    {
        $this->authorize('create', Ticket::class);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'categories' => 'required|array',
            'labels' => 'nullable|array',
            'priority' => 'required|in:low,medium,high,urgent',
            'agent_id' => 'nullable|exists:users,id'
        ]);

        $ticket = $this->ticketService->createTicket($validated);

        return response()->json($ticket, 201);
    }

    public function show(Ticket $ticket): JsonResponse
    {
        $this->authorize('view', $ticket);
        $ticket = $this->ticketService->getTicket($ticket->id);
        return response()->json($ticket);
    }

    public function update(Request $request, Ticket $ticket): JsonResponse
    {
        $this->authorize('update', $ticket);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'categories' => 'required|array',
            'labels' => 'nullable|array',
            'priority' => 'required|in:low,medium,high,urgent',
            'status' => 'required|in:open,in_progress,resolved,closed',
            'agent_id' => 'nullable|exists:users,id'
        ]);

        $ticket = $this->ticketService->updateTicket($ticket->id, $validated);

        return response()->json($ticket);
    }

    public function destroy(Ticket $ticket): JsonResponse
    {
        $this->authorize('delete', $ticket);
        $this->ticketService->deleteTicket($ticket->id);
        return response()->json(null, 204);
    }

    public function adminDashboard(): JsonResponse
    {
        $stats = $this->ticketService->getDashboardStats();
        return response()->json($stats);
    }

    public function assignAgent(Request $request, Ticket $ticket): JsonResponse
    {
        $this->authorize('assign', $ticket);

        $validated = $request->validate([
            'agent_id' => 'required|exists:users,id'
        ]);

        $ticket = $this->ticketService->assignAgent($ticket->id, $validated['agent_id']);

        return response()->json($ticket);
    }

    public function getActivityLog(Ticket $ticket): JsonResponse
    {
        $logs = $this->ticketService->getTicketActivityLogs($ticket->id);
        return response()->json($logs);
    }
}
