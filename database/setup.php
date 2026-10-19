<?php

declare(strict_types=1);

// Creates the posts table and loads the sample rows. Safe to run again.

use App\Core\Config;
use App\Core\Database;

require dirname(__DIR__) . '/vendor/autoload.php';

$config = Config::fromEnvironment();

// A SQLite file needs its folder to exist before PDO can create it.
$sqliteFile = str_starts_with($config->dsn, 'sqlite:') ? substr($config->dsn, strlen('sqlite:')) : '';
if ($sqliteFile !== '' && $sqliteFile !== ':memory:') {
    $directory = dirname($sqliteFile);
    if (!is_dir($directory) && !mkdir($directory, 0775, true)) {
        fwrite(STDERR, "Could not create {$directory}\n");
        exit(1);
    }
}

$db = Database::connect($config);

$db->execute(
    'CREATE TABLE IF NOT EXISTS posts (id INTEGER PRIMARY KEY, title VARCHAR(255) NOT NULL, content TEXT NOT NULL)',
);
$db->execute('DELETE FROM posts');

$posts = [
    [1, 'Routing', 'The router checks with reflection that a path names a public action before anything runs.'],
    [2, 'Prepared statements', 'Every value is bound with a PDO type taken from its PHP type, never joined into SQL.'],
    [3, 'Views', 'Templates are plain PHP, escaped with htmlspecialchars and rendered inside one layout.'],
];

foreach ($posts as [$id, $title, $content]) {
    $db->execute('INSERT INTO posts (id, title, content) VALUES (?, ?, ?)', [$id, $title, $content]);
}

echo 'Loaded ' . count($posts) . " posts.\n";
