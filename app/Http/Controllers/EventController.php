<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\Event\Index;
use App\Http\Requests\Event\Store;
use App\Http\Requests\Event\Update;
use App\Http\Resources\Event\EventResource;
use App\Models\Event;
use App\Repositories\EventRepository;
use Illuminate\Http\JsonResponse;
use Throwable;

class EventController extends Controller
{
    /**
     * EventController constructor.
     */
    public function __construct(private readonly EventRepository $eventRepository)
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @param Index $request
     *
     * @return JsonResponse
     */
    public function index(Index $request): JsonResponse
    {
        $attributes = $request->validated();
        $list = $this->eventRepository->getPaginatedList($attributes);

        return EventResource::collection($list)->response();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Store $request
     *
     * @return JsonResponse
     */
    public function store(Store $request): JsonResponse
    {
        $attributes = $request->validated();
        $model = $this->eventRepository->store($attributes);

        return (new EventResource($model))->response();
    }

    /**
     * Display the specified resource.
     *
     * @param Event $event
     *
     * @return JsonResponse
     */
    public function show(Event $event): JsonResponse
    {
        return (new EventResource($event))->response();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Update $request
     * @param Event $event
     *
     * @return JsonResponse
     */
    public function update(Update $request, Event $event): JsonResponse
    {
        $attributes = $request->validated();
        $event = $this->eventRepository->update($event, $attributes);

        return (new EventResource($event))->response();
    }

    /**
     *  Remove the specified resource from storage.
     *
     * @param Event $event
     *
     * @return void
     * @throws Throwable
     */
    public function destroy(Event $event): void
    {
        $this->eventRepository->destroy($event);
    }
}
