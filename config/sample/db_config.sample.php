<?php

return [
    'mysql' => [
        'host' => getenv('MYSQL_HOST'),
        'user' => getenv('MYSQL_USER'),
        'password' => getenv('MYSQL_PASSWORD') ?: '',
        'port' => getenv('MYSQL_PORT') ?: 3306,
        'database' => getenv('MYSQL_DATABASE'),
    ]
];
