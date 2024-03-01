<?php
declare(strict_types=1);

namespace App\Data\Filters;

use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

class StartEndDateFilter
{
    /**
     * StartEndDateFilter constructor.
     */
    public function __construct()
    {
    }

    /**
     * @param Builder $query
     * @param Closure $next
     *
     * @return Builder
     */
    public function __invoke(Builder $query, Closure $next): Builder
    {
        $request = request();
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        if ($startDate && $endDate) {
            $fromDate = Carbon::parse($startDate);
            $toDate = Carbon::parse($endDate);
            $fromTime = $startDate->clone()->diffInSeconds(Carbon::today());
            $toTime = $endDate->clone()->diffInSeconds(Carbon::today());
            $query->whereNotNull('repeat_until')
                ->where(
                    function ($query) use ($fromDate, $toDate, $fromTime, $toTime) {
                        $query->orWhereNull('end_date')->orWhere('end_date', '>=', $fromDate);
                        $query->whereNull('recurring_patterns.separation_count')
                            ->orWhere('recurring_patterns.separation_count', '<=', ($toDate->diffInMinutes($fromDate)) / (60 * 60 * 24));
                        $query->where(function ($q) use ($fromDate, $toDate) {
                            $q->whereNull('event_instance_exception.start_date')
                                ->orWhere(function ($q) use ($fromDate, $toDate) {
                                    $q->where('event_instance_exception.start_date', '>=', $fromDate)
                                        ->where('event_instance_exception.start_date', '<=', $toDate);
                                });
                        });
                    })
                ->where(function ($query) use ($fromDate, $toDate, $fromTime, $toTime) {
                    $query->where(function ($q) use ($fromDate, $toDate) {
                        $q->whereBetween('start_date', [$fromDate, $toDate])
                            ->orWhereBetween('repeat_until', [$fromDate, $toDate]);
                    });
                    $query->where(function ($q) use ($fromDate, $toDate, $fromTime, $toTime) {
                        $q->where(function ($q) use ($fromDate, $toDate, $fromTime) {
                            $q->where('start_date', $fromDate)->where('start_time', '>=', $fromTime);
                        })->orWhere(function ($q) use ($fromDate, $toDate) {
                            $q->where('start_date', '>', $fromDate);
                        });
                        $q->orWhere(function ($q) use ($fromDate, $toDate, $toTime) {
                            $q->where(function ($q) use ($fromDate, $toDate, $toTime) {
                                $q->where('end_date', $toDate)->where('end_time', '<=', $toTime);
                            })->orWhere(function ($q) use ($fromDate, $toDate) {
                                $q->where('end_date', '<', $toDate);
                            });
                        });
                    });
                });
        }

        return $next($query);
    }
}
