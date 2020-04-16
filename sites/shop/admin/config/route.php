<?php
return [
    'mappings' => [
        'IndexController' => ['^$'],
        'LoginController' => ['^login$'],
        'LoginController::checkAction' => ['^login\/check$'],
        'System\UserController' => ['^system\/user$'],
    ]
];