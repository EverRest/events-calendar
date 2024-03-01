<?php
declare(strict_types=1);

namespace Tests\Feature;

use App\Services\Db\EventService;
use Illuminate\Contracts\Container\BindingResolutionException;
use Soulcodex\Behat\Addon\Context;

final class EventContext extends Context
{
    /**
     * @var null|EventService $eventService
     */
    protected ?EventService $eventService = null;

    /**
     * @Given I send a request to :url
     */
    public function iSendARequestTo(string $url): void
    {
        $this->visitUrl($url);
    }

    /**
     * @throws BindingResolutionException
     */
    protected function eventService(): EventService
    {
        if(is_null($this->eventService )) {
            $this->eventService = $this->app
                ->make(EventService::class);
        }

        return $this->eventService;
    }
}
