<?php

declare(strict_types=1);

use App\Core\Config;
use App\Core\Database;
use App\Core\Router;
use App\Core\View;

$uri = $_SERVER['REQUEST_URI'] ?? '/';
$path = is_string($uri) ? parse_url($uri, PHP_URL_PATH) : null;
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

$controller = new ($route->controller)(Database::connect(Config::fromEnvironment()));
$response = $controller->{$route->method}(...$route->params);

if (!is_string($response)) {
    throw new LogicException("{$route->controller}::{$route->method}() must return a string.");
}

echo $response;
