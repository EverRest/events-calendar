<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Enums\ListRequestEnum;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use Throwable;

class Repository
{
    /**
     * @var string
     */
    protected string $model;

    /**
     * Get a list of models
     *
     * @param array $data
     *
     * @return Collection
     */
    public function getList(array $data = []): Collection
    {
        $query = $this->listQuery($data);

        return $query->get();
    }

    /**
     * Get a list of models
     *
     * @param array $data
     *
     * @return Paginator
     */
    public function getPaginatedList(array $data = []): Paginator
    {
        $query = $this->listQuery($data);

        return $this->paginate($query, $data);
    }

    /**
     * @return Builder
     */
    public function query(): Builder
    {
        /**
         * @var Model $model
         */
        $model = App::make($this->model);

        return $model::query()->lockForUpdate();
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return $this->query()->count();
    }

    /**
     * @param array $data
     *
     * @return Model
     */
    public function store(array $data): Model
    {
        return $this->model::create($data)->refresh();
    }

    /**
     * @param array $data
     *
     * @return Model
     */
    public function firstOrCreate(array $data): Model
    {
        return $this->model::firstOrCreate($data);
    }

    /**
     * Update and refresh model
     *
     * @param Model $model
     * @param array $data
     *
     * @return Model
     */
    public function update(Model $model, array $data): Model
    {
        $model->fill($data)->save();

        return $model->refresh();
    }

    /**
     * Patch and refresh model
     *
     * @param Model $model
     * @param string $fieldName
     * @param mixed $data
     *
     * @return Model
     */
    public function patch(Model $model, string $fieldName, mixed $data): Model
    {
        return $this->update($model, [$fieldName => $data]);
    }

    /**
     * Update or throw an exception if it fails.
     *
     * @param Model $model
     * @param array $data
     *
     * @return Model
     * @throws Throwable
     */
    public function updateOrFail(Model $model, array $data): Model
    {
        $model->updateOrFail($data);
        return $model;
    }

    /**
     * @param int $id
     *
     * @return Model
     */
    public function findOrFail(int $id): Model
    {
        return $this->model::findOrFail($id);
    }

    /**
     * @return void
     */
    public function destroyAll(): void
    {
        in_array(SoftDeletes::class, class_uses($this->query()->getModel()), true) ?
            $this->model::all()->each(fn(Model $model) => $model->delete()) :
            $this->model::truncate();
    }

    /**
     * Delete the model from the database within a transaction.
     *
     * @param Model $model
     * @param bool $force
     *
     * @return Model
     * @throws Throwable
     */
    public function destroy(Model $model, bool $force = false): Model
    {
        if ($force) {
            $model->forceDelete();
        } else {
            $model->deleteOrFail();
        }
        return $model;
    }


    /**
     * @param array $data
     *
     * @return Builder
     */
    protected function listQuery(array $data): Builder
    {
        $query = $this->search($data);
        $this->filter(
            $query,
            Arr::except($data, ListRequestEnum::values())
        );
        $this->sort(
            $query,
            Arr::only(
                $data,
                [
                    ListRequestEnum::sortKey->value,
                    ListRequestEnum::orderKey->value,
                ]
            ),
        );

        return $query;
    }

    /**
     * @param array $data
     *
     * @return Builder
     */
    protected function search(array $data): Builder
    {
        return $this->query();
    }

    /**
     * @param $query
     * @param array $filter
     *
     * @return Builder
     */
    protected function filter($query, array $filter): Builder
    {
        $query->when($filter, fn($query) => $this->applyFilter($query, $filter));

        return $query;
    }

    /**
     * @param mixed $query
     * @param array $filter
     *
     * @return mixed
     */
    protected function applyFilter(mixed $query, array $filter): mixed
    {
        foreach ($filter as $filterKey => $filterValue) {
            if (is_array($filterValue)) {
                $query->whereIn($filterKey, $filterValue);
            } else {
                $query->where($filterKey, $filterValue);
            }
        }

        return $query;
    }

    /**
     * @param $query
     * @param array $data
     *
     * @return Paginator
     */
    protected function paginate($query, array $data): Paginator
    {
        $limit = Arr::get($data, ListRequestEnum::limitKey->value) ?: 10;

        return $query->paginate($limit);
    }

    /**
     * @param $query
     * @param array $data
     *
     * @return Builder
     */
    protected function sort($query, array $data): Builder
    {
        $sort = $this->getSortColumn($data);
        $order = $this->getDirectionColumn($data);
        $query->when(
            $sort,
            fn($query) => $query->orderBy($sort, $order)
        );

        return $query;
    }

    /**
     * @param array $data
     *
     * @return string
     */
    protected function getSortColumn(array $data): string
    {
        return Arr::get($data, ListRequestEnum::sortKey->value, 'id');
    }

    /**
     * @param array $data
     *
     * @return string
     */
    protected function getDirectionColumn(array $data): string
    {
        return Arr::get($data, ListRequestEnum::orderKey->value, 'DESC');
    }

    /**
     * @param $query
     *
     * @return Builder
     */
    protected function with($query): Builder
    {
        return $query;
    }

    /**
     * @return Model
     */
    protected function model(): Model
    {
        return new $this->model();
    }
}
