<?php
return [
    'global' => [
        'AuthInterceptor',
    ],
    'default' => [

    ],
    'App\\Controller\\LoginController@indexAction' => [
        'App\\Interceptor\\LoggerInterceptor',
    ]
];