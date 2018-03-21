<?php

return [
    'database' => [
        'connection' => true,
        'host'       => 'localhost',
        'username'   => 'kerntaak',
        'password'   => 'kerntaak',
        'dbname'     => 'kerntaak'
    ],

    'view'    => [
        'view.path'      => 'views',
        'view.extension' => '.view',
        'view.template'  => 'layout',
    ],

    'csrf'    => [
        'csrf_active' => true,
        'csrf_name'   => 'csrf_token'
    ],

    'local_time_zone' => 'Europe/Bucharest',

    'cookie'  => [
       'cookie_name'   => 'user_key',
       'cookie_expiry' => time() + (86400 * 7) // 7 days
    ],
];
