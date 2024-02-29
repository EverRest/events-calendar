<?php
declare(strict_types=1);

namespace App\Repositories;

use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use Throwable;

interface IRepository
{
    /**
     * Get a list of models
     *
     * @param array $data
     *
     * @return Collection
     */
    public function getList(array $data = []): Collection;

    /**
     * Get a list of models
     *
     * @param array $data
     *
     * @return Paginator
     */
    public function getPaginatedList(array $data = []): Paginator;

    /**
     * @return Builder
     */
    public function query(): Builder;

    /**
     * @return int
     */
    public function count(): int;

    /**
     * @param array $data
     *
     * @return Model
     */
    public function store(array $data): Model;

    /**
     * @param array $data
     *
     * @return Model
     */
    public function firstOrCreate(array $data): Model;

    /**
     * Update and refresh model
     *
     * @param Model $model
     * @param array $data
     *
     * @return Model
     */
    public function update(Model $model, array $data): Model;

    /**
     * Patch and refresh model
     *
     * @param Model $model
     * @param string $fieldName
     * @param mixed $data
     *
     * @return Model
     */
    public function patch(Model $model, string $fieldName, mixed $data): Model;

    /**
     * Update or throw an exception if it fails.
     *
     * @param Model $model
     * @param array $data
     *
     * @return Model
     * @throws Throwable
     */
    public function updateOrFail(Model $model, array $data): Model;

    /**
     * @param int $id
     *
     * @return Model
     */
    public function findOrFail(int $id): Model;

    /**
     * @return void
     */
    public function destroyAll(): void;

    /**
     * Delete the model from the database within a transaction.
     *
     * @param Model $model
     * @param bool $force
     *
     * @return Model
     * @throws Throwable
     */
    public function destroy(Model $model, bool $force = false): Model;
}
