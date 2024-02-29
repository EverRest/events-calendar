<?php
declare(strict_types=1);

namespace App\Services;

use App\Data\Transformers\EventTransformer;
use App\Data\Transformers\RecurringPatternTransformer;
use App\Models\Event;
use App\Repositories\EventRepository;
use Exception;
use Illuminate\Support\Facades\DB;

final class EventService extends ServiceWithRepository
{
    /**
     * @param EventRepository $eventRepository
     */
    public function __construct(EventRepository $eventRepository)
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
            $eventDto =EventTransformer::from($attributes);
            $event = parent::create($eventDto->toEventModel());
            if($eventDto->isRecurring){
                $recurringPatternDto =RecurringPatternTransformer::from($attributes);
                $event->recurringPattern()->create(
                    $recurringPatternDto->toRecurringPatternModel()
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
