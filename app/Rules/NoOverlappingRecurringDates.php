<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\Event;
use App\Models\RecurringPattern;

class NoOverlappingRecurringDates implements Rule
{
    /**
     * @var int|null
     */
    protected ?int $eventId = null;

    /**
     * @param int|null $eventId
     */
    public function __construct(int $eventId = null)
    {
        $this->eventId = $eventId;
    }

    /**
     * @param $attribute
     * @param $value
     *
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        $overlappingEvents = Event::where(function ($query) use ($value) {
            $query->where('start_date', '<=', $value['end'])
                ->where('end_date', '>=', $value['start'])
                ->where(function ($query) use ($value) {
                    $query->where('end_time', '>', $value['start_time'])
                        ->orWhere(function ($query) use ($value) {
                            $query->where('end_date', '>', $value['start_date'])
                                ->where('end_time', '<=', $value['start_time']);
                        });
                })
                ->where(function ($query) use ($value) {
                    $query->where('start_time', '<', $value['end_time'])
                        ->orWhere(function ($query) use ($value) {
                            $query->where('start_date', '<', $value['end_date'])
                                ->where('start_time', '>=', $value['end_time']);
                        });
                });
        })->whereNotIn('id', [$this->eventId])->count();
        if ($overlappingEvents > 0) return false;
        $overlappingPatterns = RecurringPattern::where(function ($query) use ($value) {
            $query->where('event_id', '<>', $this->eventId)
                ->where(function ($query) use ($value) {
                    $query->where('start_date', '<=', $value['end_date'])
                        ->where('end_date', '>=', $value['start_date'])
                        ->where(function ($query) use ($value) {
                            $query->where('end_time', '>', $value['start_time'])
                                ->orWhere(function ($query) use ($value) {
                                    $query->where('end_date', '>', $value['start_date'])
                                        ->where('end_time', '<=', $value['start_time']);
                                });
                        })
                        ->where(function ($query) use ($value) {
                            $query->where('start_time', '<', $value['end_time'])
                                ->orWhere(function ($query) use ($value) {
                                    $query->where('start_date', '<', $value['end_date'])
                                        ->where('start_time', '>=', $value['end_time']);
                                });
                        });
                })
                ->where(function ($query) {
                    $query->where('separation_count', '>', 0)
                        ->orWhereNull('separation_count');
                });
        })->count();

        return $overlappingPatterns === 0;
    }

    /**
     * @return string
     */
    public function message(): string
    {
        return 'The recurring dates overlap with existing events or patterns.';
    }
}
