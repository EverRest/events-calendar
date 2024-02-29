<?php
declare(strict_types=1);

namespace App\Data\Transformers;

use App\Data\Casts\CarbonDate;
use Illuminate\Support\Carbon;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Data;

class RecurringPatternTransformer extends Data
{
    /**
     * @param Carbon $start
     * @param Carbon $end
     * @param Carbon|null $repeat_until
     * @param string $frequency
     */
    public function __construct(
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
        public readonly string $frequency,
    )
    {
    }

    /**
     * @return array
     */
    public function toRecurringPatternModel(): array
    {
        return [
//            'title' => $this->title,
//            'description' => $this->description,
//            'start_date' => $this->start,
//            'end_date' => $this->end,
//            'start_time' => $this->start,
//            'end_time' => $this->repeat_until,
        ];
    }
}
