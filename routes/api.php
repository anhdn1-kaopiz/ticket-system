<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TicketController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

// Auth routes
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);

    // Profile
    Route::get('/profile', [ProfileController::class, 'show']);
    Route::put('/profile', [ProfileController::class, 'update']);
    Route::delete('/profile', [ProfileController::class, 'destroy']);

    // Tickets
    Route::get('/tickets', [TicketController::class, 'index']);
    Route::post('/tickets', [TicketController::class, 'store'])->middleware('can:create,App\Models\Ticket');
    Route::get('/tickets/{ticket}', [TicketController::class, 'show'])->middleware('can:view,ticket');
    Route::put('/tickets/{ticket}', [TicketController::class, 'update'])->middleware('can:update,ticket');
    Route::delete('/tickets/{ticket}', [TicketController::class, 'destroy'])->middleware('can:delete,ticket');
    Route::get('/tickets/{ticket}/comments', [CommentController::class, 'index']);
    Route::post('/tickets/{ticket}/comments', [CommentController::class, 'store']);
    Route::put('/comments/{comment}', [CommentController::class, 'update']);
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy']);

    // Admin routes
    Route::middleware(['admin'])->prefix('admin')->group(function () {
        Route::get('/dashboard', [TicketController::class, 'adminDashboard']);
        Route::patch('/tickets/{ticket}/assign', [TicketController::class, 'assignAgent']);
        Route::get('/tickets/{ticket}/activity-logs', [TicketController::class, 'getActivityLog']);

        // User management routes
        Route::get('/users', [UserController::class, 'index']);
        Route::get('/users/admins', [UserController::class, 'getAdmins']);
        Route::get('/users/agents', [UserController::class, 'getAgents']);
        Route::get('/users/regular', [UserController::class, 'getRegularUsers']);
        Route::get('/users/{user}', [UserController::class, 'show']);
        Route::patch('/users/{user}/role', [UserController::class, 'updateRole']);
    });
});
