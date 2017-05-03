<?php
return [
    'global' => [
        'App\\Interceptor\\AuthorizeInterceptor',
    ],
    'default' => [

    ],
    'App\\Controller\\LoginController@indexAction' => [
        '!App\\Interceptor\\AuthorizeInterceptor',
    ],
    'App\\Controller\\LoginController@checkAction' => [
        '!App\\Interceptor\\AuthorizeInterceptor',
    ],
    'App\\Controller\\LoginController@logoutAction' => [
        '!App\\Interceptor\\AuthorizeInterceptor',
    ],
];
