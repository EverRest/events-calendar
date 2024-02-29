<?php
declare(strict_types=1);

namespace App\Services\Db;

use App\Models\Event;
use App\Repositories\IRepository;
use Illuminate\Contracts\Pagination\Paginator;
use Throwable;

class ServiceWithRepository
{
    protected IRepository  $repository;


    /**
     * @param array $attributes
     *
     * @return Paginator
     */
    public function index(array $attributes): Paginator
    {
        return $this->repository->getPaginatedList($attributes);
    }

    /**
     * @param array $attributes
     *
     * @return Event
     */
    public function create(array $attributes): Event
    {
        /**@var Event $event */
        $event = $this->repository->store($attributes);

        return $event;
    }

    /**
     * @param Event $event
     * @param array $attributes
     *
     * @return Event
     * @throws Throwable
     */
    public function update(Event $event, array $attributes): Event
    {
        /**@var Event $event */
        $event =  $this->repository->updateOrFail($event, $attributes);

        return $event;
    }

    /**
     * @param Event $event
     *
     * @return Event
     * @throws Throwable
     */
    public function delete(Event $event): Event
    {
        /**@var Event $event */
        $event =  $this->repository->destroy($event);

        return $event;
    }
}
