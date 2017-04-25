<?php
return [
    'db_cms' => [
        'master' => [
            'server'      => '127.0.0.1',
            'database_name'  => 'cms',
            'username'  => 'root',
            'password'  => '123456',
        ],
        'slave' => [
            [
                'server'      => '127.0.0.1',
                'database_name'  => 'cms',
                'username'  => 'root',
                'password'  => '12345678',
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
            'host' => 'redis.server.com',
            'port' => 13230,
            'timeout' => 0,
            'persistent' => false,
        ],
        'slave' => [
            [
                'host' => 'redis.server.com',
                'port' => 13230,
                'timeout' => 0,
                'persistent' => false,
            ]
        ]
    ]
];