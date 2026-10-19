<?php

declare(strict_types=1);

// Settings come from the environment; the default is a local SQLite file.
return [
    'db' => [
        'dsn' => getenv('DB_DSN') ?: 'sqlite:' . dirname(__DIR__) . '/var/galaxy.sqlite',
        'user' => getenv('DB_USER') ?: null,
        'password' => getenv('DB_PASSWORD') ?: null,
    ],
];
