<?php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecurringPattern extends Model
{
    use HasFactory;

    /**
     * @var string[] $fillable
     */
    protected $fillable = [
        'event_id',
        'recurring_type_id',
        'separation_count',
        'max_num_of_occurrences',
        'day_of_week',
        'week_of_month',
        'month_of_year',
    ];
}
