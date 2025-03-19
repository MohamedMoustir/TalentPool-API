<?php
namespace App\Repositories;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;

class UserRepository 
{
    protected $model;

    public function __construct(User $model)
    {
        $this->model = $model;
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function findById($id)
    {
        return $this->model->find($id);
    }

    public function findByEmail($email)
    {
        return $this->model->where('email', $email)->first();
    }
}
