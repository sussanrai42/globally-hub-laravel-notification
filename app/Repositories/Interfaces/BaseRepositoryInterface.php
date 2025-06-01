<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface BaseRepositoryInterface
{
    public function newQuery(): Builder;

    /**
     * Find a model by ID or throw a ModelNotFoundException if not found.
     *
     * @param string|int $id
     * @return Model
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findOrFail(string|int $id): object;

    /**
     * Find a model by given column and value.
     *
     * @param string $column
     * @param string|int $value
     * @return ?Model
     */
    public function findBy(string $column, string|int $value): ?object;

    /**
     * Create a new model in the database.
     *
     * @param array $data
     * @return Model
     */
    public function create(array $data): object;

    /**
     * Update a model in the database.
     *
     * @param string|int $id
     * @param array $data
     * @return Model
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function update(string|int $id, array $data): object;

    /**
     * Delete a model from the database.
     *
     * @param string|int $id
     * @return bool
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function delete(string|int $id): bool;

    /**
     * Paginate the models with optional filterable parameters.
     *
     * @param array $filterable The array of filterable parameters, such as 'per_page' and 'page'.
     * @return LengthAwarePaginator The paginator instance containing the paginated results.
     */
    public function paginate(array $filterable = []): LengthAwarePaginator;
}
