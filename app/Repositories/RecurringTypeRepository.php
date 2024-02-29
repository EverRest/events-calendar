<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Models\RecurringType;

final class RecurringTypeRepository extends Repository
{
    /**
     * @var string  $model
     */
    protected string $model = RecurringType::class;

    /**
     * @param string $code
     *
     * @return RecurringType|null
     */
    public function getRecurringTypeByCode(string $code): ?RecurringType
    {
        /** @var RecurringType|null $model */
        $model = $this->query()->where('code', $code)->first();

        return $model;
    }
}
