<?php

declare(strict_types=1);

namespace App\Core;

/**
 * Base class for controllers. Every public method on a subclass is a route.
 */
abstract class Controller
{
    public function __construct(protected readonly Database $db)
    {
    }

    /**
     * @param array<string, mixed> $data
     */
    protected function render(string $view, array $data = []): string
    {
        return View::render($view, $data);
    }
}
