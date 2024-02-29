<?php
declare(strict_types=1);

namespace App\Data\Api\Put;

use App\Data\Casts\CarbonDate;
use App\Models\Event;
use Illuminate\Support\Carbon;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;

class EventTransformer extends Data
{
    /**
     * @var bool $isRecurring
     */
    public bool $isRecurring;

    /**
     * @param string|Optional $title
     * @param string|Optional $description
     * @param Carbon|Optional $start
     * @param Carbon|Optional $end
     * @param Carbon|Optional $repeat_until
     * @param string|Optional $frequency
     */
    public function __construct(
        #[MapInputName('event')]
        public readonly Event       $event,
        #[MapInputName('title')]
        public readonly string|null $title,
        #[MapInputName('description')]
        public readonly string|null $description,
        #[WithCast(CarbonDate::class)]
        #[MapInputName('start')]
        public readonly Carbon|null $start,
        #[WithCast(CarbonDate::class)]
        #[MapInputName('end')]
        public readonly Carbon|null $end,
        #[WithCast(CarbonDate::class)]
        #[MapInputName('repeat_until')]
        public readonly Carbon|null $repeat_until,
        #[MapInputName('frequency')]
        public readonly string|null $frequency,
    )
    {
        $this->isRecurring = $this->frequency && $this->repeat_until;
    }

    /**
     * @return array
     */
    public function toEventAttributes(): array
    {
        $dtoAttributes = [
            'title' => $this->title,
            'description' => $this->description,
            'start_date' => $this->start,
            'end_date' => $this->repeat_until ?? $this->end,
            'start_time' => $this->start?->diffInSeconds(Carbon::today()),
            'end_time' => $this->repeat_until?->diffInSeconds(Carbon::today()),
            'repeat_until' => $this->repeat_until,
        ];

        return array_filter($dtoAttributes, function ($attribute) {
            return $attribute !== null && $attribute !== '';
        });
    }
}
