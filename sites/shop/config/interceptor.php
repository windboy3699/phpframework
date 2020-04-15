<?php
return [
    'global' => [
        'shop\\admin\\interceptor\\AuthorizeInterceptor',
    ],
    'default' => [

    ],
    'shop\\admin\\LoginController' => [
        '!shop\\admin\\interceptor\\AuthorizeInterceptor',
    ],
    'shop\\admin\\LoginController::checkAction' => [
        '!shop\\admin\\interceptor\\AuthorizeInterceptor',
    ],
    'shop\\admin\\LoginController::logoutAction' => [
        '!shop\\admin\\interceptor\\AuthorizeInterceptor',
    ],
];
