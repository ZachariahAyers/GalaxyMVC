<?php

declare(strict_types=1);

use App\Core\BasePath;
use App\Core\Config;
use App\Core\Database;
use App\Core\Router;
use App\Core\View;

require dirname(__DIR__) . '/vendor/autoload.php';

$uri = $_SERVER['REQUEST_URI'] ?? '/';
$path = is_string($uri) ? parse_url($uri, PHP_URL_PATH) : null;
$base = BasePath::fromEnvironment();
$path = $base->strip(is_string($path) ? $path : '/');

// Under `php -S`, existing files in public/ are served as files. The server finds them only at the
// root, so under a base path this script sends them itself, and only the types listed here: never
// PHP source or .htaccess.
if (PHP_SAPI === 'cli-server' && $path !== null) {
    $file = realpath(__DIR__ . $path);
    if ($file !== false && str_starts_with($file, __DIR__ . DIRECTORY_SEPARATOR) && is_file($file)) {
        if ($base->prefix === '') {
            return false;
        }
        $type = ['css' => 'text/css; charset=UTF-8'][pathinfo($file, PATHINFO_EXTENSION)] ?? null;
        if ($type !== null) {
            header("Content-Type: {$type}");
            readfile($file);
            return;
        }
    }
}

$route = $path === null ? null : (new Router())->resolve($path);

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
