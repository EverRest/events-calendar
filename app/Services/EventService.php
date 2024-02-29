<?php
declare(strict_types=1);

namespace App\Services;

use App\Models\Event;
use App\Repositories\EventRepository;
use Illuminate\Contracts\Pagination\Paginator;
use Throwable;

class EventService
{
    /**
     * @param EventRepository $eventRepository
     */
    public function __construct(private readonly EventRepository $eventRepository)
    {
    }

    /**
     * @param array $attributes
     *
     * @return Paginator
     */
    public function index(array $attributes): Paginator
    {
        return $this->eventRepository->getPaginatedList($attributes);
    }

    /**
     * @param array $attributes
     *
     * @return Event
     */
    public function create(array $attributes): Event
    {
        /**@var Event $event */
        $event = $this->eventRepository->store($attributes);

        return $event;
    }

    /**
     * @param Event $event
     * @param array $attributes
     *
     * @return Event
     */
    public function update(Event $event, array $attributes): Event
    {
        /**@var Event $event */
        $event =  $this->eventRepository->update($event, $attributes);

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
        $event =  $this->eventRepository->destroy($event);

        return $event;
    }
}
