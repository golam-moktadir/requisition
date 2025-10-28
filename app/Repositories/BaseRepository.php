<?php

namespace App\Repositories;

// use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use App\Interface\BaseRepositoryInterface;

class BaseRepository implements BaseRepositoryInterface
{
    protected $model;

    /**
     * BaseRepository constructor.
     * 
     * @param  Model  $model
     */
    // public function __construct(Model $model)
    // {
    //     $this->model = $model;
    // }

    /**
     * Count the number of specified model records in the database
     *
     * @return int
     */
    public function count()
    {
        return $this->model->count();
    }

    /**
     * Get all records
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAll(): Collection
    {
        return $this->model->all();
    }    

    /**
     * Find a record by its ID
     *
     * @param  int  $id
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function find(int $id)
    {
        return $this->model->find($id);
    }

    public function getById(int $id)
    {
        return $this->model->find($id);
    }

    /**
     * Create a new record
     *
     * @param  array  $data
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function create(array $data)
    {
        return $this->model->create($data);
    }

    /**
     * Update a record by its ID
     *
     * @param  int  $id
     * @param  array  $data
     * @return bool
     */
    public function update(int $id, array $data)
    {
        $model = $this->find($id);

        if ($model) {
            return $model->update($data);
        }

        return false;
    }

    /**
     * Delete a record by its ID
     *
     * @param  int  $id
     * @return bool|null
     */
    public function delete(int $id)
    {
        $model = $this->find($id);

        return $model ? $model->delete() : false;
    }
}
