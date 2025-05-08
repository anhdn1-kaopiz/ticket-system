<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Interfaces\TicketRepositoryInterface;
use App\Repositories\TicketRepository;
use App\Repositories\Interfaces\CommentRepositoryInterface;
use App\Repositories\CommentRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(TicketRepositoryInterface::class, TicketRepository::class);
        $this->app->bind(CommentRepositoryInterface::class, CommentRepository::class);
    }

    public function boot(): void
    {
        //
    }
}
