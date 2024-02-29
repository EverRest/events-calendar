<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Models\Event;

final class EventRepository extends Repository
{
    /**
     * @var string  $model
     */
    protected string $model = Event::class;
}
