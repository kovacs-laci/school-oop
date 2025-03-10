<?php

return [
    'default' => 'mysql', // Default database connection

    'connections' => [
        'mysql' => [
            'host' => '127.0.0.1',
            'port' => 3306,
            'database' => 'school',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
        ],

        'sqlite' => [
            'database' => ':memory:',
        ],
    ],
];
