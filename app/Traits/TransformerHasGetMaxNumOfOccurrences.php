<?php
declare(strict_types=1);

namespace App\Traits;

use App\Enums\RecurringTypeEnum;

trait TransformerHasGetMaxNumOfOccurrences
{
    /**
     * @return int
     */
    protected function getMaxNumOfOccurrences(): int
    {
        return match ($this->recurring_type_model->recurring_type) {
            RecurringTypeEnum::DAILY->value => $this->repeat_until
                ->diffInDays($this->start),
            RecurringTypeEnum::WEEKLY->value => $this->repeat_until
                ->diffInWeeks($this->start),
            RecurringTypeEnum::MONTHLY->value => $this->repeat_until
                ->diffInMonths($this->start),
            RecurringTypeEnum::YEARLY->value => $this->repeat_until
                ->diffInYears($this->start),
        };
    }
}
