<?php
declare(strict_types=1);

namespace App\Data\Transformers;

use App\Data\Casts\CarbonDate;
use Illuminate\Support\Carbon;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Data;

class EventTransformer extends Data
{
    /**
     * @var bool  $isRecurring
     */
    public bool $isRecurring;

    /**
     * @param string $title
     * @param string $description
     * @param Carbon $start
     * @param Carbon $end
     * @param Carbon|null $repeat_until
     * @param string|null $frequency
     */
    public function __construct(
        #[MapInputName('title')]
        public readonly string $title,
        #[MapInputName('description')]
        public readonly string $description,
        #[WithCast(CarbonDate::class)]
        #[MapInputName('start')]
        public readonly Carbon $start,
        #[WithCast(CarbonDate::class)]
        #[MapInputName('end')]
        public readonly Carbon $end,
        #[WithCast(CarbonDate::class)]
        #[MapInputName('repeat_until')]
        public readonly Carbon|null $repeat_until,
        #[MapInputName('frequency')]
        public readonly string|null  $frequency,
    )
    {
        $this->isRecurring = $this->frequency && $this->repeat_until;
    }

    /**
     * @return array
     */
    public function toEventModel(): array
    {
        return [
            'title' => $this->title,
            'description' => $this->description,
            'start_date' => $this->start,
            'end_date' => $this->repeat_until??$this->end,
            'start_time' => $this->start,
            'end_time' => $this->repeat_until,
        ];
    }
}
