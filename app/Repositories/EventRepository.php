<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Data\Filters\EventFilterPipeline;
use App\Data\Filters\StartEndDateFilter;
use App\Models\Event;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;

final class EventRepository extends Repository
{
    /**
     * @var string $model
     */
    protected string $model = Event::class;

    /**
     * @var array $with
     */
    protected array $with = ['recurringPattern.recurringType',];

    /**
     * @param $query
     * @param array $filter
     *
     * @return Builder
     */
    protected function filter($query, array $filter): Builder
    {
        $filters = [
            StartEndDateFilter::class => [
                'start' => Arr::get($filter, 'start'),
                'end' => Arr::get($filter, 'end'),
            ],
        ];
        $pipeline = new EventFilterPipeline($filters);
        $query = $pipeline->apply($query);

        return parent::filter($query, Arr::except($filter, ['start', 'end']));
    }
}
