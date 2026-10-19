<?php

declare(strict_types=1);

// Creates the posts table and loads the sample rows. Safe to run again.

use App\Core\Database;

require dirname(__DIR__) . '/vendor/autoload.php';

$config = require dirname(__DIR__) . '/app/config.php';

if (str_starts_with($config['db']['dsn'], 'sqlite:')) {
    $directory = dirname(substr($config['db']['dsn'], strlen('sqlite:')));
    if (!is_dir($directory) && !mkdir($directory, 0775, true)) {
        fwrite(STDERR, "Could not create {$directory}\n");
        exit(1);
    }
}

$db = Database::connect($config['db']['dsn'], $config['db']['user'], $config['db']['password']);

$db->execute('CREATE TABLE IF NOT EXISTS posts (
    id INTEGER PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL
)');
$db->execute('DELETE FROM posts');

$posts = [
    [
        1,
        'Routing',
        'The router splits the path into controller, method and parameters, then checks with reflection'
            . ' that the target is a public action before anything runs.',
    ],
    [
        2,
        'Prepared statements',
        'Every query goes through PDO::prepare, and each value is bound with a type taken from its PHP type,'
            . ' so user input never becomes SQL.',
    ],
    [
        3,
        'Views',
        'Templates are plain PHP files. Output is escaped with htmlspecialchars and rendered inside one shared layout.',
    ],
];

foreach ($posts as [$id, $title, $content]) {
    $db->execute('INSERT INTO posts (id, title, content) VALUES (?, ?, ?)', [$id, $title, $content]);
}

echo 'Loaded ' . count($posts) . " posts.\n";
