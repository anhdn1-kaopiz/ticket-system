<?php

namespace App\Policies;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TicketPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return true;
    }

    public function view(User $user, Ticket $ticket)
    {
        return $user->id === $ticket->user_id ||
            $user->id === $ticket->agent_id ||
            $user->isAdmin() ||
            $user->isAgent();
    }

    public function create(User $user)
    {
        return true;
    }

    public function update(User $user, Ticket $ticket)
    {
        return $user->isAdmin() ||
            $user->id === $ticket->agent_id;
    }

    public function delete(User $user, Ticket $ticket)
    {
        return $user->isAdmin();
    }

    public function assign(User $user, Ticket $ticket)
    {
        return $user->isAdmin();
    }

    public function addComment(User $user, Ticket $ticket)
    {
        return $user->id === $ticket->user_id ||
            $user->id === $ticket->agent_id ||
            $user->isAdmin();
    }
}
