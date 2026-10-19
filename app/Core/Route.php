<?php

declare(strict_types=1);

namespace App\Core;

/**
 * The controller action a request path resolved to.
 */
final readonly class Route
{
    /**
     * @param class-string<Controller> $controller
     * @param list<string>             $params
     */
    public function __construct(
        public string $controller,
        public string $method,
        public array $params = [],
    ) {
    }
}
