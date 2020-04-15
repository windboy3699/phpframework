<?php
return [
    'mappings' => [
        'shop\admin\IndexController' => ['^$'],
        'shop\admin\LoginController' => ['^login$'],
        'shop\admin\LoginController::checkAction' => ['^login\/check$'],
    ]
];