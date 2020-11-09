<?php


namespace App\Services\Logger;


use Exception;
use Illuminate\Support\Facades\Facade;

/**
 * @method static info(Exception $exception, string $string)
 * @method static error(Exception $exception, string $string)
 */
class Logger extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'logger';
    }
}
