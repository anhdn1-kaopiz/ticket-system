<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Repositories\Interfaces\TicketRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Notifications\NewTicketNotification;

class TicketService
{
    protected $ticketRepository;

    public function __construct(TicketRepositoryInterface $ticketRepository)
    {
        $this->ticketRepository = $ticketRepository;
    }

    public function getAllTickets()
    {
        return $this->ticketRepository->all();
    }

    public function getTicket($id)
    {
        return $this->ticketRepository->find($id);
    }

    public function createTicket(array $data)
    {
        $data['user_id'] = Auth::id();
        $ticket = $this->ticketRepository->create($data);

        // Log the creation
        ActivityLog::create([
            'ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'action' => 'created',
            'description' => 'Ticket created',
            'new_values' => $data
        ]);

        // Notify admins
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new NewTicketNotification($ticket));
        }

        return $ticket;
    }

    public function updateTicket($id, array $data)
    {
        $oldTicket = $this->ticketRepository->find($id);
        $ticket = $this->ticketRepository->update($id, $data);

        // Log the changes
        ActivityLog::create([
            'ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'action' => 'updated',
            'description' => 'Ticket updated',
            'old_values' => $oldTicket->toArray(),
            'new_values' => $data
        ]);

        return $ticket;
    }

    public function deleteTicket($id)
    {
        $ticket = $this->ticketRepository->find($id);

        // Log the deletion
        ActivityLog::create([
            'ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'action' => 'deleted',
            'description' => 'Ticket deleted',
            'old_values' => $ticket->toArray()
        ]);

        return $this->ticketRepository->delete($id);
    }

    public function assignAgent($ticketId, $agentId)
    {
        $oldTicket = $this->ticketRepository->find($ticketId);
        $ticket = $this->ticketRepository->assignAgent($ticketId, $agentId);

        // Log the assignment
        ActivityLog::create([
            'ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'action' => 'assigned',
            'description' => 'Agent assigned to ticket',
            'old_values' => ['agent_id' => $oldTicket->agent_id],
            'new_values' => ['agent_id' => $agentId]
        ]);

        return $ticket;
    }

    public function getAssignedTickets($agentId)
    {
        return $this->ticketRepository->getAssignedTickets($agentId);
    }

    public function addComment($ticketId, $commentData)
    {
        $comment = $this->ticketRepository->addComment($ticketId, $commentData);

        // Log the comment
        ActivityLog::create([
            'ticket_id' => $ticketId,
            'user_id' => Auth::id(),
            'action' => 'commented',
            'description' => 'Comment added',
            'new_values' => $commentData
        ]);

        return $comment;
    }

    public function getDashboardStats()
    {
        return [
            'total_tickets' => $this->ticketRepository->count(),
            'open_tickets' => $this->ticketRepository->countByStatus('open'),
            'in_progress_tickets' => $this->ticketRepository->countByStatus('in_progress'),
            'resolved_tickets' => $this->ticketRepository->countByStatus('resolved'),
            'closed_tickets' => $this->ticketRepository->countByStatus('closed'),
            'low_priority_tickets' => $this->ticketRepository->countByPriority('low'),
            'medium_priority_tickets' => $this->ticketRepository->countByPriority('medium'),
            'high_priority_tickets' => $this->ticketRepository->countByPriority('high'),
            'urgent_priority_tickets' => $this->ticketRepository->countByPriority('urgent'),
        ];
    }

    public function getTicketActivityLog($ticketId)
    {
        return ActivityLog::where('ticket_id', $ticketId)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getFilteredTickets(array $filters, $user)
    {
        return $this->ticketRepository->getFilteredTickets($filters, $user);
    }

    public function getTicketActivityLogs($ticketId)
    {
        return $this->ticketRepository->getTicketActivityLogs($ticketId);
    }
}
