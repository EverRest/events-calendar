<?php
declare(strict_types=1);

namespace App\Data\Api\Post;

use App\Data\Casts\CarbonDate;
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
     * @param string|Optional|null $frequency
     */
    public function __construct(
        #[MapInputName('title')]
        public readonly string|Optional $title,
        #[MapInputName('description')]
        public readonly string|Optional $description,
        #[WithCast(CarbonDate::class)]
        #[MapInputName('start')]
        public readonly Carbon|Optional $start,
        #[WithCast(CarbonDate::class)]
        #[MapInputName('end')]
        public readonly Carbon|Optional $end,
        #[WithCast(CarbonDate::class)]
        #[MapInputName('repeat_until')]
        public readonly Carbon|Optional $repeat_until,
        #[MapInputName('frequency')]
        public readonly string|null |Optional     $frequency,
    )
    {
        $this->isRecurring = $this->frequency && $this->repeat_until;
    }

    /**
     * @return array
     */
    public function toEventAttributes(): array
    {
        return [
            'title' => $this->title,
            'description' => $this->description,
            'start_date' => $this->start,
            'end_date' => $this->repeat_until ?? $this->end,
            'start_time' => $this->start->diffInSeconds(Carbon::today()),
            'end_time' => $this->end->diffInSeconds(Carbon::today()),
            'repeat_until' => $this->repeat_until,
        ];
    }
}
