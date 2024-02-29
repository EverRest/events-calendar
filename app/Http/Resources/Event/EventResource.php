<?php

namespace App\Http\Resources\Event;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

/** @mixin Event */
class EventResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'start' => Carbon::parse($this->start_date)
                ->addSeconds($this->start_time)
                ->format('Y-m-d H:i'),
            'end' => Carbon::parse($this->end_date)
                ->addSeconds($this->end_time)
                ->format('Y-m-d H:i'),
            'repeat_until' => $this->repeat_until,
            'frequency' => $this->recurringPattern?->recurringType?->recurring_type,
            'is_recurring' => boolval($this->recurringPattern),
            'recurring_pattern' => $this->recurringPattern,
        ];
    }
}
