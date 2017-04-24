<?php
return [
    'db_sports' => [
        'master' => [
            'server'      => 'master.db.com',
            'database_name'  => 'demo',
            'username'  => 'user',
            'password'  => 'pwd',
        ],
        'slave' => [
            [
                'server'      => 'slave.db.com',
                'database_name'  => 'demo',
                'username'  => 'user',
                'password'  => 'pwd',
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