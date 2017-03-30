<?php
/**
 * 不需要带Controller/Action后缀
 * 去掉后缀后大小写和类中的保持一致
 */
return [
    'mappings' => [
        'Mobile_V1_Hello@show' => [
            '^dem(o)$',
        ],
    ]
];