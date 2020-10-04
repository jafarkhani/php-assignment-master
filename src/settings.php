<?php

return [
    'settings' => [
        'displayErrorDetails' => true,
        'addContentLengthHeader' => false,
        'logger' => [
            'name' => 'app',
            'path' => __DIR__ . '/../logs/app.log',
            'level' => Monolog\Logger::DEBUG,
        ],
        'mongo' => [
            'host' => 'project1.ntlrs.mongodb.net',
            'port' => '',
            'user' => 'dbuser',
            'password' => 'dbpass12976',
            'database' => 'project1',
        ],
    ],
];

