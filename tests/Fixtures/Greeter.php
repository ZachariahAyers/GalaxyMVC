<?php

declare(strict_types=1);

namespace Tests\Fixtures;

use App\Core\Controller;

/**
 * Controller used by RouterTest to cover parameters and non-routable methods.
 */
final class Greeter extends Controller
{
    public function index(): string
    {
        return 'index';
    }

    public function hello(string $name): string
    {
        return "Hello, {$name}";
    }

    public function between(string $from, string $to = 'end'): string
    {
        return "{$from}-{$to}";
    }

    public static function make(): string
    {
        return 'static';
    }

    protected function secret(): string
    {
        return 'secret';
    }
}
