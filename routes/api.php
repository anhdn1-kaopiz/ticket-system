<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TicketController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::middleware('auth:sanctum')->group(function () {
    // Auth routes
    Route::prefix('auth')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/user', [AuthController::class, 'user']);
    });

    // Profile routes
    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'show']);
        Route::put('/', [ProfileController::class, 'update']);
        Route::delete('/', [ProfileController::class, 'destroy']);
    });

    // Ticket routes
    Route::prefix('tickets')->group(function () {
        Route::get('/', [TicketController::class, 'index']);
        Route::post('/', [TicketController::class, 'store'])->middleware('can:create,App\Models\Ticket');
        Route::get('/{ticket}', [TicketController::class, 'show'])->middleware('can:view,ticket');
        Route::put('/{ticket}', [TicketController::class, 'update'])->middleware('can:update,ticket');
        Route::delete('/{ticket}', [TicketController::class, 'destroy'])->middleware('can:delete,ticket');

        // Ticket comments
        Route::get('/{ticket}/comments', [CommentController::class, 'index']);
        Route::post('/{ticket}/comments', [CommentController::class, 'store']);
    });

    // Comment routes
    Route::prefix('comments')->group(function () {
        Route::put('/{comment}', [CommentController::class, 'update']);
        Route::delete('/{comment}', [CommentController::class, 'destroy']);
    });

    // Admin routes
    Route::middleware(['admin'])->prefix('admin')->group(function () {
        // Dashboard
        Route::get('/dashboard', [TicketController::class, 'adminDashboard']);

        // Ticket management
        Route::prefix('tickets')->group(function () {
            Route::patch('/{ticket}/assign', [TicketController::class, 'assignAgent']);
            Route::get('/{ticket}/activity-logs', [TicketController::class, 'getActivityLog']);
        });

        // User management
        Route::prefix('users')->group(function () {
            Route::get('/', [UserController::class, 'index']);
            Route::get('/admins', [UserController::class, 'getAdmins']);
            Route::get('/agents', [UserController::class, 'getAgents']);
            Route::get('/regular', [UserController::class, 'getRegularUsers']);
            Route::get('/{user}', [UserController::class, 'show']);
            Route::patch('/{user}/role', [UserController::class, 'updateRole']);
        });
    });
});
