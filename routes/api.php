<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TicketController;
use App\Http\Controllers\Api\ProfileController;
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

    // Admin routes
    Route::middleware(['admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [TicketController::class, 'adminDashboard']);
        Route::patch('/tickets/{ticket}/assign', [TicketController::class, 'assignAgent']);
    });
});
