<?php
declare(strict_types=1);

namespace App\Data\Api\Put;

use App\Data\Casts\CarbonDate;
use App\Models\RecurringType;
use App\Traits\TransformerHasGetMaxNumOfOccurrences;
use App\Traits\TransformerHasGetSeparationCount;
use Illuminate\Support\Carbon;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Data;

class RecurringPatternTransformer extends Data
{
    use TransformerHasGetMaxNumOfOccurrences;
    use TransformerHasGetSeparationCount;

    /**
     * @param Carbon|null $start
     * @param Carbon|null $end
     * @param Carbon|null $repeat_until
     * @param RecurringType|null $recurring_type_model
     */
    public function __construct(
        #[WithCast(CarbonDate::class)]
        #[MapInputName('start')]
        public readonly Carbon|null        $start,
        #[WithCast(CarbonDate::class)]
        #[MapInputName('end')]
        public readonly Carbon|null        $end,
        #[WithCast(CarbonDate::class)]
        #[MapInputName('repeat_until')]
        public readonly Carbon|null        $repeat_until,
        #[MapInputName('recurring_type')]
        public readonly RecurringType|null $recurring_type_model,
    )
    {
    }

    /**
     * @return array
     */
    public function toRecurringPatternAttributes(): array
    {
        $dtoAttributes = [
            'recurring_type_id' => $this->recurring_type_model->id,
            'separation_count' => $this->getSeparationCount(),
            'max_num_of_occurrences' => $this->getMaxNumOfOccurrences(),
            'day_of_week' => $this->start->dayOfWeek,
            'week_of_month' => $this->start->weekOfMonth,
            'month_of_year' => $this->start->month,
        ];

        return array_filter($dtoAttributes, function ($attribute) {
            return $attribute !== null && $attribute !== '';
        });
    }
}
