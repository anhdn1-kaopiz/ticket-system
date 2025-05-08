<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    protected $model;

    public function __construct(User $model)
    {
        $this->model = $model;
    }

    public function all()
    {
        return $this->model->all();
    }

    public function find($id)
    {
        return $this->model->findOrFail($id);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update($id, array $data)
    {
        $user = $this->find($id);
        $user->update($data);
        return $user;
    }

    public function delete($id)
    {
        return $this->model->destroy($id);
    }

    public function updateRole($id, string $role)
    {
        $user = $this->find($id);
        $user->role = $role;
        $user->save();
        return $user;
    }

    public function getAdmins()
    {
        return $this->model->where('role', 'admin')->get();
    }

    public function getAgents()
    {
        return $this->model->where('role', 'agent')->get();
    }

    public function getRegularUsers()
    {
        return $this->model->where('role', 'user')->get();
    }
}
