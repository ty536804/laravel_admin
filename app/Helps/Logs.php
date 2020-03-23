<?php
/**
 * Created by PhpStorm.
 * User: caoxiaobin
 * Time: 2:27 PM
 */
namespace App\Helpers;

use Illuminate\Support\Facades\Facade;

/**
 * @method static void emergency(string $message, array $context = [],$file='')
 * @method static void alert(string $message, array $context = [],$file='')
 * @method static void critical(string $message, array $context = [],$file='')
 * @method static void error(string $message, array $context = [],$file='')
 * @method static void warning(string $message, array $context = [],$file='')
 * @method static void notice(string $message, array $context = [],$file='')
 * @method static void info(string $message, array $context = [],$file='')
 * @method static void debug(string $message, array $context = [],$file='')
 * @method static void log($level, string $message, array $context = [],$file='')
 * @method static \Psr\Log\LoggerInterface stack(array $channels, string $channel = null)
 * fuck
 * @see \Illuminate\Log\Logger
 */

class Logs extends Facade {
    protected static function getFacadeAccessor()
    {
        return Loge::class;
    }

}
