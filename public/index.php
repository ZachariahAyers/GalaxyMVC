<?php

declare(strict_types=1);

use App\Core\Database;
use App\Core\Router;
use App\Core\View;

$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
$path = is_string($path) ? $path : '/';

// Under `php -S`, hand existing files in public/ back to the server.
if (PHP_SAPI === 'cli-server') {
    $file = realpath(__DIR__ . $path);
    if ($file !== false && str_starts_with($file, __DIR__ . DIRECTORY_SEPARATOR) && is_file($file)) {
        return false;
    }
}

require dirname(__DIR__) . '/vendor/autoload.php';

$route = (new Router())->resolve($path);

if ($route === null) {
    http_response_code(404);
    echo View::render('errors/404', ['title' => 'Not found']);
    return;
}

$config = require dirname(__DIR__) . '/app/config.php';
$db = Database::connect($config['db']['dsn'], $config['db']['user'], $config['db']['password']);

$controller = new ($route->controller)($db);
echo $controller->{$route->method}(...$route->params);
