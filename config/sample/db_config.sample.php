<?php

return [
    'mysql' => [
        'host' => getenv('DB_HOST'),
        'user' => getenv('DB_USER'),
        'password' => getenv('DB_PASSWORD'),
        'port' => getenv('DB_PORT'),
        'database' => getenv('DB_DATABASE'),
    ]
];
