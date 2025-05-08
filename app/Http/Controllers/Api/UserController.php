<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index(): JsonResponse
    {
        $users = $this->userService->getAllUsers();
        return response()->json($users);
    }

    public function show(User $user): JsonResponse
    {
        return response()->json($user);
    }

    public function updateRole(Request $request, User $user): JsonResponse
    {
        $validated = $request->validate([
            'role' => 'required|string|in:admin,agent,user'
        ]);

        try {
            $updatedUser = $this->userService->updateUserRole($user->id, $validated['role']);
            return response()->json($updatedUser);
        } catch (\InvalidArgumentException $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function getAdmins(): JsonResponse
    {
        $admins = $this->userService->getAdmins();
        return response()->json($admins);
    }

    public function getAgents(): JsonResponse
    {
        $agents = $this->userService->getAgents();
        return response()->json($agents);
    }

    public function getRegularUsers(): JsonResponse
    {
        $users = $this->userService->getRegularUsers();
        return response()->json($users);
    }
}
