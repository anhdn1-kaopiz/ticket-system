<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Services\TicketService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class TicketController extends Controller
{
    use AuthorizesRequests;

    protected $ticketService;

    public function __construct(TicketService $ticketService)
    {
        $this->ticketService = $ticketService;
    }

    public function index()
    {
        $tickets = $this->ticketService->getAllTickets();
        return view('tickets.index', compact('tickets'));
    }

    public function create()
    {
        $this->authorize('create', Ticket::class);
        return view('tickets.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', Ticket::class);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'categories' => 'required|array',
            'labels' => 'nullable|array',
            'priority' => 'required|in:low,medium,high,urgent'
        ]);

        $ticket = $this->ticketService->createTicket($validated);

        return redirect()->route('tickets.show', $ticket)
            ->with('success', 'Ticket created successfully.');
    }

    public function show(Ticket $ticket)
    {
        $this->authorize('view', $ticket);
        $ticket = $this->ticketService->getTicket($ticket->id);
        return view('tickets.show', compact('ticket'));
    }

    public function edit(Ticket $ticket)
    {
        $this->authorize('update', $ticket);
        return view('tickets.edit', compact('ticket'));
    }

    public function update(Request $request, Ticket $ticket)
    {
        $this->authorize('update', $ticket);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'categories' => 'required|array',
            'labels' => 'nullable|array',
            'priority' => 'required|in:low,medium,high,urgent'
        ]);

        $this->ticketService->updateTicket($ticket->id, $validated);

        return redirect()->route('tickets.show', $ticket)
            ->with('success', 'Ticket updated successfully.');
    }

    public function assignAgent(Request $request, Ticket $ticket)
    {
        $this->authorize('assign', $ticket);

        $validated = $request->validate([
            'agent_id' => 'required|exists:users,id'
        ]);

        $this->ticketService->assignAgent($ticket->id, $validated['agent_id']);

        return redirect()->route('tickets.show', $ticket)
            ->with('success', 'Agent assigned successfully.');
    }
}
