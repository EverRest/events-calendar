<?php
declare(strict_types=1);

namespace App\Exceptions;

use Exception;

class OverlappingEventException extends Exception
{
    protected $message = 'The date is overlapping with another event';
}
