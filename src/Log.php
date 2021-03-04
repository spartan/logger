<?php

namespace Spartan\Logger;

use Monolog\Handler\HandlerInterface;
use Monolog\Logger;
use Psr\Log\LoggerInterface;

/**
 * Logger Facade
 *
 * @method static void log($message, array $context = [])
 * @method static void debug($message, array $context = [])
 * @method static void info($message, array $context = [])
 * @method static void notice($message, array $context = [])
 * @method static void warning($message, array $context = [])
 * @method static void error($message, array $context = [])
 * @method static void critical($message, array $context = [])
 * @method static void alert($message, array $context = [])
 * @method static void emergency($message, array $context = [])
 *
 * @package Spartan\Logger
 * @author  Iulian N. <iulian@spartanphp.com>
 * @license LICENSE MIT
 */
class Log
{
    /**
     * @param string $name
     *
     * @return LoggerInterface
     */
    public static function instance(string $name = 'default')
    {
        return new Logger($name, self::channelHandlers($name));
    }

    /**
     * @param string $name
     *
     * @return HandlerInterface[]
     */
    public static function channelHandlers(string $name = 'default'): array
    {
        $config = require './config/log.php';

        $handlers = [];
        foreach ($config[$name] ?? [] as $index => $handler) {
            if (is_string($handler)) {
                $handlers[] = new $handler;
            } elseif (is_array($handler)) {
                $handlers[] = new $index(...array_values($handler));
            } elseif ($handler instanceof \Closure) {
                $handlers[] = $handler();
            }
        }

        return array_filter($handlers);
    }

    /**
     * @param string $name
     * @param mixed  $args
     *
     * @return mixed
     */
    public static function __callStatic(string $name, $args)
    {
        if (in_array($name, ['log', 'debug', 'info', 'error']) && count($args) && !is_scalar($args[0])) {
            $args[0] = json_encode($args[0]);
        }

        return self::instance()->{$name}(...$args);
    }
}
