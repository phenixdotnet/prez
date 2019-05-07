<?php


namespace App\Logging;


use Illuminate\Log\Logger;
use Monolog\Processor\WebProcessor;

class PushWebProcessor
{
    /**
     * Pushed uid processor for adding a unique identifier into records.
     *
     * @param  \Illuminate\Log\Logger $logger
     *
     * @return void
     */
    public function __invoke(Logger $logger)
    {
        collect($logger->getHandlers())->each(function ($handler) {
            $handler->pushProcessor(new WebProcessor());
        });
    }
}