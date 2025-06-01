<?php

namespace App\Repositories;

use App\Rules\StringOrIntegerRule;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Validator;
use App\Repositories\Interfaces\BaseRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

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

    /**
     * Paginate the models with optional filterable parameters.
     *
     * @param array $filterable The array of filterable parameters, such as 'per_page' and 'page'.
     * @return LengthAwarePaginator The paginator instance containing the paginated results.
     */
    public function paginate(array $filterable = []): LengthAwarePaginator
    {
        $this->validateFilterable($filterable);

        $perPage = $filterable['per_page'] ?? 10;
        $sortBy = $filterable['sort_by'] ?? 'created_at';
        $sortOrder = $filterable['sort_order'] ?? 'desc';
        return $this->model->query()
            ->where($filterable['filters'] ?? [])
            ->orderBy($sortBy, $sortOrder)
            ->paginate($perPage)
            ->appends(request()->except("page"));
    }

    /**
     * Validate the filterable parameters for pagination.
     *
     * @param array $filterable The array of filterable parameters.
     * @return array The validated filterable parameters.
     */
    protected function validateFilterable(array $filterable): array
    {
        return Validator::make($filterable, [
            'sort_by' => 'sometimes|string',
            'sort_order' => 'sometimes|in:asc,desc',
            'per_page' => 'sometimes|integer|min:1|max:100',
            'page' => 'sometimes|integer|min:1',
            'filters' => 'sometimes|array',
            'filters.*.field' => 'required|string',
            'filters.*.value' => ['required', new StringOrIntegerRule],
        ])->validate();
    }
}
