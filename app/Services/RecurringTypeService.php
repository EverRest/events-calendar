<?php
declare(strict_types=1);

namespace App\Services;

use App\Models\RecurringType;
use App\Repositories\RecurringTypeRepository;

final class RecurringTypeService extends ServiceWithRepository
{
    /**
     * @param RecurringTypeRepository $recurringTypeRepository
     */
    public function __construct( RecurringTypeRepository $recurringTypeRepository)
    {
        $this->repository = $recurringTypeRepository;
    }

    /**
     * @param string $code
     *
     * @return RecurringType|null
     */
    public function getRecurringTypeByCode(string $code): ?RecurringType
    {
        return $this->repository->getRecurringTypeByCode($code);
    }
}
