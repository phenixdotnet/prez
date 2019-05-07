<?php

use Monolog\Handler\StreamHandler;
use Monolog\Handler\SyslogUdpHandler;

return [

    /*
    |--------------------------------------------------------------------------
    | Default Log Channel
    |--------------------------------------------------------------------------
    |
    | This option defines the default log channel that gets used when writing
    | messages to the logs. The name specified in this option should match
    | one of the channels defined in the "channels" configuration array.
    |
    */

    'default' => env('LOG_CHANNEL', 'stack'),

    /*
    |--------------------------------------------------------------------------
    | Log Channels
    |--------------------------------------------------------------------------
    |
    | Here you may configure the log channels for your application. Out of
    | the box, Laravel uses the Monolog PHP logging library. This gives
    | you a variety of powerful log handlers / formatters to utilize.
    |
    | Available Drivers: "single", "daily", "slack", "syslog",
    |                    "errorlog", "monolog",
    |                    "custom", "stack"
    |
    */

    'channels' => [
        'stack' => [
            'driver' => 'stack',
            'channels' => ['daily'],
            'ignore_exceptions' => false,
        ],

        'daily' => [
            'driver' => 'daily',
            'path' => env('LOGS_FILE_PATH', storage_path('logs/laravel.log')),
            'level' => 'debug',
            'days' => 14,
        ],

        'elasticsearch' => [
            'driver' => 'custom',
            'level' => 'debug',
            'via' => \App\Logging\CreateElasticSearchLogger::class,
            'tap' => [ \App\Logging\PushWebProcessor::class ],
            'config' => [
                'connections' => [
                    [ 'host' => env('LOGS_ELASTICSEARCH_URL', 'localhost'), 'port' => env('LOGS_ELASTICSEARCH_PORT', 9200) ]
                ],
                'index' => 'monapp'
            ]
        ],
    ],

];
