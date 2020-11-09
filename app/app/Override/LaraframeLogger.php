<?php

namespace App\Override;


use Exception;

class LaraframeLogger
{
    public $logger;

    public function __construct(\Illuminate\Log\Logger $logger)
    {
        $this->logger = $logger;
    }

    public function info(Exception $exception, $prefix = "")
    {
        $this->logger->info("{$prefix} MESSAGE: {$exception->getMessage()} FILE: {$exception->getFile()} LINE: {$exception->getLine()}");
    }

    public function error(Exception $exception, $prefix = "")
    {
        $this->logger->error("{$prefix} MESSAGE: {$exception->getMessage()} FILE: {$exception->getFile()} LINE: {$exception->getLine()}");
    }

}
