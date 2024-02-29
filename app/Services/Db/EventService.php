<?php
declare(strict_types=1);

namespace App\Services\Db;

use App\Data\Api\Post\EventTransformer as PostEventTransformer;
use App\Data\Api\Put\EventTransformer as PutEventTransformer;
use App\Data\Api\Post\RecurringPatternTransformer as PostRecurringPatternTransformer;
use App\Data\Api\Put\RecurringPatternTransformer as PutRecurringPatternTransformer;
use App\Models\Event;
use App\Repositories\EventRepository;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Throwable;

final class EventService extends ServiceWithRepository
{
    /**
     * @param EventRepository $eventRepository
     * @param RecurringTypeService $recurringTypeService
     */
    public function __construct(
        EventRepository                       $eventRepository,
        private readonly RecurringTypeService $recurringTypeService,
    )
    {
        $this->repository = $eventRepository;
    }

    /**
     * @param array $attributes
     *
     * @return Event
     * @throws Exception
     */
    public function create(array $attributes): Event
    {
        DB::beginTransaction();
        try {
            $eventDto = PostEventTransformer::from($attributes);
            $event = parent::create($eventDto->toEventAttributes());
            if ($eventDto->isRecurring) {
                $recurringTypeModel = $this->recurringTypeService->getRecurringTypeByCode($eventDto->frequency);
                $recurringPatternData = [
                    ...Arr::only($attributes, ['start', 'end', 'repeat_until',]),
                    'recurring_type' => $recurringTypeModel,
                ];
                $recurringPatternDto = PostRecurringPatternTransformer::from($recurringPatternData);
                $event->recurringPattern()->create(
                    $recurringPatternDto->toRecurringPatternAttributes()
                );
            }
            DB::commit();

            return $event;
        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
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
        DB::beginTransaction();
        try {
            $data = [...$attributes, 'event' => $event];
            $eventDto = PutEventTransformer::from($data);
            $event->update($eventDto->toEventAttributes());
            if ($eventDto->isRecurring) {
                $recurringTypeModel = $this->recurringTypeService->getRecurringTypeByCode($eventDto->frequency);
                $recurringPatternData = [
                    ...Arr::only($attributes, ['start', 'end', 'repeat_until',]),
                    'recurring_type' => $recurringTypeModel,
                ];
                $recurringPatternDto = PutRecurringPatternTransformer::from($recurringPatternData);
                $event->recurringPattern()->updateOrCreate(
                    ['event_id' => $event->id],
                    $recurringPatternDto->toRecurringPatternAttributes()
                );
            }
            DB::commit();

            return $event;
        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }
}
