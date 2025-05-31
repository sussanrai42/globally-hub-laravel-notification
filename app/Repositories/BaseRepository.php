<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Repositories\Interfaces\BaseRepositoryInterface;

abstract class BaseRepository implements BaseRepositoryInterface
{
    protected Model $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function newQuery(): Builder
    {
        return $this->model->newQuery();
    }

    /**
     * Find a model by ID or throw a ModelNotFoundException if not found.
     *
     * @param string|int $id
     * @return Model
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findOrFail(string|int $id): object
    {
        return $this->model->findOrFail($id);
    }

    /**
     * Find a model by given column and value.
     *
     * @param string $column
     * @param string|int $value
     * @return ?Model
     */
    public function findBy(string $column, string|int $value): ?object
    {
        return $this->model->where($column, $value)->first();
    }

    /**
     * Create a new model in the database.
     *
     * @param array $data
     * @return Model
     */
    public function create(array $data): object
    {
        return $this->model->create($data);
    }

    /**
     * Update a model in the database.
     *
     * @param string|int $id
     * @param array $data
     * @return Model
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function update(string|int $id, array $data): object
    {
        $model = $this->findOrFail($id);
        $model->fill($data);
        $model->save();
        return $model;
    }

    /**
     * Delete a model from the database.
     *
     * @param string|int $id
     * @return bool
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function delete(string|int $id): bool
    {
        return $this->findOrFail($id)->delete();
    }
}
