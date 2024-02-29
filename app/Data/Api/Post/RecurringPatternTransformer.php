<?php
declare(strict_types=1);

namespace App\Data\Api\Post;

use App\Data\Casts\CarbonDate;
use App\Enums\RecurringTypeEnum;
use App\Models\RecurringType;
use Illuminate\Support\Carbon;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Data;

class RecurringPatternTransformer extends Data
{
    /**
     * @param Carbon $start
     * @param Carbon $end
     * @param Carbon $repeat_until
     * @param RecurringType $recurring_type_model
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
        public readonly Carbon $repeat_until,
        #[MapInputName('recurring_type')]
        public readonly RecurringType $recurring_type_model,
    )
    {
    }

    /**
     * @return array
     */
    public function toRecurringPatternAttributes(): array
    {
        return [
            'recurring_type_id' => $this->recurring_type_model->id,
            'separation_count' => 0,
            'max_num_of_occurrences' => $this->getMaxNumOfOccurrences(),
            'day_of_week' => $this->start->dayOfWeek,
            'week_of_month' => $this->start->weekOfMonth,
            'month_of_year' => $this->start->month,
        ];
    }

    /**
     * @return int
     */
    private function getMaxNumOfOccurrences(): int
    {
        return match($this->recurring_type_model->recurring_type) {
           RecurringTypeEnum::DAILY->value => $this->repeat_until
               ->diffInDays($this->start),
            RecurringTypeEnum::WEEKLY->value =>  $this->repeat_until
                ->diffInWeeks($this->start),
            RecurringTypeEnum::MONTHLY->value => $this->repeat_until
                ->diffInMonths($this->start),
            RecurringTypeEnum::YEARLY->value => $this->repeat_until
                ->diffInYears($this->start),
        };
    }
}
