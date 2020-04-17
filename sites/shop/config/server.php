<?php
return [
    'db_shop' => [
        'master' => [
            'server'      => '127.0.0.1',
            'database_name'  => 'shop',
            'username'  => 'root',
            'password'  => 'root123456',
        ],
        'slave' => [
            [
                'server'      => '127.0.0.1',
                'database_name'  => 'shop',
                'username'  => 'root',
                'password'  => 'root123456',
            ]
        ]
    ],

    'memcache' => [
        'keyPrefix' => 'app.api.',
        'servers' => [
            [
                'host' => '127.0.0.1',
                'port' => '11211',
                'weight' => 1,
            ]
        ]
    ],

    'redis' => [
        'keyPrefix' => 'app.api.',
        'master' => [
            'host' => '127.0.0.1',
            'port' => 6379,
            'timeout' => 0,
            'persistent' => false,
        ],
        'slave' => [
            [
                'host' => '127.0.0.1',
                'port' => 6379,
                'timeout' => 0,
                'persistent' => false,
            ]
        ]
    ],

    'session' => [
        'use_cookies'   => 1,
        'cookie_path'   => '/',
        'cookie_domain' => '.shop.com',
        'gc_maxlifetime'=> 86400,
    ],

    'kafka' => [
        'brokers' => '127.0.0.1',
    ]
];