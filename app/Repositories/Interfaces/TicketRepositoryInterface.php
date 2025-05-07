<?php

namespace App\Repositories\Interfaces;

interface TicketRepositoryInterface
{
    public function all();
    public function find($id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
    public function assignAgent($ticketId, $agentId);
    public function getAssignedTickets($agentId);
    public function addComment($ticketId, $commentData);
    public function count();
    public function countByStatus(string $status);
    public function countByPriority(string $priority);
}
