<?php

declare(strict_types=1);

namespace App\Core;

use ReflectionException;
use ReflectionMethod;

/**
 * Maps /controller/method/param1/param2 to a public controller action.
 *
 * Missing segments fall back to the default controller and method. A path
 * that names a class or method that is not a routable action resolves to null,
 * which the front controller answers with a 404.
 */
final class Router
{
    private const SEGMENT = '/^[A-Za-z][A-Za-z0-9]*$/';

    public function __construct(
        private readonly string $controllerNamespace = 'App\\Controllers\\',
        private readonly string $defaultController = 'Home',
        private readonly string $defaultMethod = 'index',
    ) {
    }

    public function resolve(string $path): ?Route
    {
        $segments = array_values(array_filter(
            explode('/', $path),
            static fn (string $segment): bool => $segment !== '',
        ));

        $name = $segments[0] ?? $this->defaultController;
        $method = $segments[1] ?? $this->defaultMethod;
        $params = array_map(rawurldecode(...), array_slice($segments, 2));

        if (preg_match(self::SEGMENT, $name) !== 1 || preg_match(self::SEGMENT, $method) !== 1) {
            return null;
        }

        $class = $this->controllerNamespace . ucfirst($name);
        if (!class_exists($class) || !is_subclass_of($class, Controller::class)) {
            return null;
        }

        try {
            $action = new ReflectionMethod($class, $method);
        } catch (ReflectionException) {
            return null;
        }

        if (
            !$action->isPublic()
            || $action->isStatic()
            || count($params) < $action->getNumberOfRequiredParameters()
            || count($params) > $action->getNumberOfParameters()
        ) {
            return null;
        }

        return new Route($class, $action->getName(), $params);
    }
}
