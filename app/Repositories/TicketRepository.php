<?php

namespace App\Repositories;

use App\Models\Ticket;
use App\Repositories\Interfaces\TicketRepositoryInterface;

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
        $ticket->assigned_to = $agentId;
        $ticket->save();
        return $ticket;
    }

    public function getAssignedTickets($agentId)
    {
        return $this->model->where('assigned_to', $agentId)
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
}
