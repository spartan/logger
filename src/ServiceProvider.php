<?php

namespace Spartan\Logger;

use Monolog\Logger;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Spartan\Service\Container;
use Spartan\Service\Definition\ProviderInterface;
use Spartan\Service\Pipeline;

/**
 * ServiceProvider Logger
 *
 * @package Spartan\Logger
 * @author  Iulian N. <iulian@spartanphp.com>
 * @license LICENSE MIT
 */
class ServiceProvider implements ProviderInterface
{
    /**
     * @return mixed[]
     */
    public function singletons(): array
    {
        return [
            'logger'               => LoggerInterface::class,
            LoggerInterface::class => function ($c) {
                $stack = getenv('LOG_STACK') ?: 'default';
                return new Logger($stack, Log::channelHandlers($stack));
            },
        ];
    }

    /**
     * @return mixed[]
     */
    public function prototypes(): array
    {
        return [];
    }

    /**
     * @param ContainerInterface $container
     * @param Pipeline           $handler
     *
     * @return ContainerInterface
     */
    public function process(ContainerInterface $container, Pipeline $handler): ContainerInterface
    {
        /** @var Container $container */
        return $handler->handle(
            $container->withBindings($this->singletons(), $this->prototypes())
        );
    }
}
