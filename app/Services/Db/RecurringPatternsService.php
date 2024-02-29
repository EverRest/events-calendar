<?php
declare(strict_types=1);

namespace App\Services\Db;

use App\Repositories\EventRepository;

final class RecurringPatternsService extends ServiceWithRepository
{
    /**
     * @param EventRepository $eventRepository
     */
    public function __construct( EventRepository $eventRepository)
    {
        $this->repository = $eventRepository;
    }
}
