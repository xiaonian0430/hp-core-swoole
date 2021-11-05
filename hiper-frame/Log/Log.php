<?php
/**
 * @author: Xiao Nian
 * @contact: xiaonian030@163.com
 * @datetime: 2019-12-01 14:00
 */
namespace HP\Log;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Formatter\LineFormatter;
/**
 * 日志
 *
 * @method static void log($level, $message, array $context = [])
 * @method static void debug($message, array $context = [])
 * @method static void info($message, array $context = [])
 * @method static void notice($message, array $context = [])
 * @method static void warning($message, array $context = [])
 * @method static void error($message, array $context = [])
 * @method static void critical($message, array $context = [])
 * @method static void alert($message, array $context = [])
 * @method static void emergency($message, array $context = [])
 */
class Log {
    private static $instance;
    public static function getInstance() {
        if(!isset(static::$instance)){
            static::$instance = new Logger('APP');

            $stream = new StreamHandler(CONFIG['LOG_PATH'], Logger::DEBUG);
            // the default date format is "Y-m-d\TH:i:sP"
            $dateFormat = "Y-m-d H:i:s";
            //"[%datetime%] %channel%.%level_name%: %message% %context% %extra%\n"
            $output = "[%datetime%] %channel%.%level_name%: %message% %context%\n";
            $formatter = new LineFormatter($output, $dateFormat);
            $stream->setFormatter($formatter);
            static::$instance->pushHandler($stream);
        }
        return static::$instance;
    }

    /**
     * @param $name
     * @param $arguments
     * @return mixed
     */
    public static function __callStatic($name, $arguments)
    {
        return static::getInstance()->{$name}(... $arguments);
    }
}