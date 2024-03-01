<?php
declare(strict_types=1);

namespace App\Traits;

use App\Enums\RecurringTypeEnum;

trait TransformerHasGetSeparationCount
{
    /**
     * @return int
     */
    protected function getSeparationCount(): int
    {
        return match ($this->recurring_type_model->recurring_type) {
            RecurringTypeEnum::DAILY->value => 0,
            RecurringTypeEnum::WEEKLY->value => 1,
            RecurringTypeEnum::MONTHLY->value => 1,
            RecurringTypeEnum::YEARLY->value => 1,
        };
    }
}
