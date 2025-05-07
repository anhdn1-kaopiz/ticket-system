<?php

namespace App\Services;

use App\Repositories\Interfaces\TicketRepositoryInterface;
use Illuminate\Support\Facades\Auth;

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
        return $this->ticketRepository->create($data);
    }

    public function updateTicket($id, array $data)
    {
        return $this->ticketRepository->update($id, $data);
    }

    public function deleteTicket($id)
    {
        return $this->ticketRepository->delete($id);
    }

    public function assignAgent($ticketId, $agentId)
    {
        return $this->ticketRepository->assignAgent($ticketId, $agentId);
    }

    public function getAssignedTickets($agentId)
    {
        return $this->ticketRepository->getAssignedTickets($agentId);
    }

    public function addComment($ticketId, $commentData)
    {
        return $this->ticketRepository->addComment($ticketId, $commentData);
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
}
