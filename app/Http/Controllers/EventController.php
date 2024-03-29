<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\Event\Index;
use App\Http\Requests\Event\Store;
use App\Http\Requests\Event\Update;
use App\Http\Resources\Event\EventResource;
use App\Models\Event;
use App\Services\Db\EventService;
use Exception;
use Illuminate\Http\JsonResponse;
use Throwable;

class EventController extends Controller
{
    /**
     * EventController constructor.
     */
    public function __construct(private readonly EventService $eventService)
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
        $list = $this->eventService->index($attributes);

        return EventResource::collection($list)->response();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Store $request
     *
     * @return JsonResponse
     * @throws Exception
     */
    public function store(Store $request): JsonResponse
    {
        $attributes = $request->validated();
        $model = $this->eventService->create($attributes);

        return (new EventResource($model))->response();
    }

    /**
     * Put the specified resource in storage.
     *
     * @param Update $request
     * @param Event $event
     *
     * @return JsonResponse
     * @throws Throwable
     */
    public function update(Update $request, Event $event): JsonResponse
    {
        $attributes = $request->validated();
        $event = $this->eventService->update($event, $attributes);

        return (new EventResource($event))->response();
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
     *  Remove the specified resource from storage.
     *
     * @param Event $event
     *
     * @return JsonResponse
     * @throws Throwable
     */
    public function destroy(Event $event): JsonResponse
    {
        $event = $this->eventService->delete($event);

        return (new EventResource($event))->response();
    }
}
