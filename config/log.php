<?php

/*
 * List of channels and associated handlers for Monolog
 *
 *  'stack-name' => [
 *      Monolog\Handler\ErrorLogHandler::class,
 *      Monolog\Handler\SyslogHandler::class,
 *  ],
 *  'another-stack-name' => [
 *      Monolog\Handler\PushoverHandler::class => [
 *          'token' => getenv('PUSHOVER_API_KEY'),
 *          'users' => 'me'
 *      ]
 *  ],
 *  'custom-stack-name' => [
 *      function () {
 *          return (new CustomHandler())->pushFormatter(new CustomFormatter());
 *      }
 *  ]
 */

return [
    'default' => [
        Monolog\Handler\ErrorLogHandler::class,
        Monolog\Handler\StreamHandler::class => [
            'file' => getenv('LOG_FILE')
        ]
    ],

    'local' => [
        function () {
            $file = getenv('LOG_WHOOPS') ?: './data/logs/whoops.html';
            return (new Monolog\Handler\StreamHandler($file))
                ->setFormatter(new Spartan\Logger\Formatter\Whoops());
        },
    ]
];
