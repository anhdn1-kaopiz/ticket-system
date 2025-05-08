<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class UserService
{
    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function updateUserRole($userId, string $role)
    {
        // Validate role
        if (!in_array($role, ['admin', 'agent', 'user'])) {
            throw new \InvalidArgumentException('Invalid role specified');
        }

        $oldUser = $this->userRepository->find($userId);
        $user = $this->userRepository->updateRole($userId, $role);
        
        return $user;
    }

    public function getAllUsers()
    {
        return $this->userRepository->all();
    }

    public function getUser($id)
    {
        return $this->userRepository->find($id);
    }

    public function getAdmins()
    {
        return $this->userRepository->getAdmins();
    }

    public function getAgents()
    {
        return $this->userRepository->getAgents();
    }

    public function getRegularUsers()
    {
        return $this->userRepository->getRegularUsers();
    }
}
