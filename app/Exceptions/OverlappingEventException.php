<?php
declare(strict_types=1);

namespace App\Exceptions;

use Exception;

class OverlappingEventException extends Exception
{
    /**
     * @var string $message
     */
    protected $message = 'The date of the current event is overlapping with another event. Please choose another date.';
}
