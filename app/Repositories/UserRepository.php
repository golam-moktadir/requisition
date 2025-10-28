<?php 

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Interface\UserRepositoryInterface;
use Doctrine\DBAL\Connection;

class UserRepository implements UserRepositoryInterface
{
    protected $model;
    private $ddb;

    public function __construct(User $user, Connection $ddb)
    {
        $this->model = $user;
        $this->ddb = $ddb;
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
        $user = $this->find($id);
        return $user->delete();
    }
}

