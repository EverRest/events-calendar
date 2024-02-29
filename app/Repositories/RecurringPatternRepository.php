<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Models\RecurringPattern;

final class RecurringPatternRepository extends Repository
{
    /**
     * @var string  $model
     */
    protected string $model = RecurringPattern::class;
}
