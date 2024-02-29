<?php
declare(strict_types=1);

namespace App\Enums;

use App\Traits\EnumHasToArray;

enum RecurringPatternsEnum: string
{
    use EnumHasToArray;

    case DAILY = 'daily';
    case WEEKLY = 'weekly';
    case MONTHLY = 'monthly';
    case YEARLY = 'yearly';
//    case WEEKDAYS = 'weekdays';
//    case HOLIDAYS = 'holidays';
}
