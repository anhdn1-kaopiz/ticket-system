<?php

namespace App\Repositories;

use App\Models\Ticket;
use App\Repositories\Interfaces\TicketRepositoryInterface;
use App\Models\ActivityLog;

class TicketRepository implements TicketRepositoryInterface
{
    protected $model;

    public function __construct(Ticket $model)
    {
        $this->model = $model;
    }

    public function all()
    {
        return $this->model->with(['creator', 'assignee', 'categories', 'labels'])->get();
    }

    public function find($id)
    {
        return $this->model->with(['creator', 'assignee', 'categories', 'labels', 'comments'])->findOrFail($id);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update($id, array $data)
    {
        $ticket = $this->find($id);
        $ticket->update($data);
        return $ticket;
    }

    public function delete($id)
    {
        return $this->model->destroy($id);
    }

    public function assignAgent($ticketId, $agentId)
    {
        $ticket = $this->find($ticketId);
        $ticket->agent_id = $agentId;
        $ticket->save();
        return $ticket;
    }

    public function getAssignedTickets($agentId)
    {
        return $this->model->where('agent_id', $agentId)
            ->with(['creator', 'categories', 'labels'])
            ->get();
    }

    public function addComment($ticketId, $commentData)
    {
        $ticket = $this->find($ticketId);
        return $ticket->comments()->create($commentData);
    }

    public function count()
    {
        return $this->model->count();
    }

    public function countByStatus(string $status)
    {
        return $this->model->where('status', $status)->count();
    }

    public function countByPriority(string $priority)
    {
        return $this->model->where('priority', $priority)->count();
    }

    public function getFilteredTickets(array $filters, $user)
    {
        $query = $this->model->query();

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }
        if (isset($filters['priority'])) {
            $query->where('priority', $filters['priority']);
        }
        if (isset($filters['category_id'])) {
            $query->whereHas('categories', function ($q) use ($filters) {
                $q->where('categories.id', $filters['category_id']);
            });
        }

        if ($user->role === 'admin') {
            return $this->getAdminTickets($query);
        } elseif ($user->role === 'agent') {
            return $this->getAgentTickets($user->id, $query);
        } else {
            return $this->getUserTickets($user->id, $query);
        }
    }

    public function getUserTickets($userId, $query = null)
    {
        $query = $query ?? $this->model->query();
        return $query->where('user_id', $userId)
            ->with(['assignee', 'categories', 'labels'])
            ->latest()
            ->paginate(10);
    }

    public function getAdminTickets($query = null)
    {
        $query = $query ?? $this->model->query();
        return $query->with(['creator', 'assignee', 'categories', 'labels'])
            ->latest()
            ->paginate(10);
    }

    public function getAgentTickets($agentId, $query = null)
    {
        $query = $query ?? $this->model->query();
        return $query->where('agent_id', $agentId)
            ->with(['creator', 'categories', 'labels'])
            ->latest()
            ->paginate(10);
    }

    public function getTicketActivityLogs($ticketId)
    {
        return ActivityLog::where('ticket_id', $ticketId)
            ->with(['user', 'ticket'])
            ->orderBy('created_at', 'desc')
            ->get();
    }
}
