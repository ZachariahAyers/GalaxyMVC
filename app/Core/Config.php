<?php

declare(strict_types=1);

namespace App\Core;

/**
 * Database settings read from the environment. The default is a SQLite file
 * in var/, so the project runs with no setup beyond `composer setup`.
 */
final readonly class Config
{
    public function __construct(
        public string $dsn,
        public ?string $user = null,
        #[\SensitiveParameter] public ?string $password = null,
    ) {
    }

    public static function fromEnvironment(): self
    {
        return new self(
            self::env('DB_DSN') ?? 'sqlite:' . dirname(__DIR__, 2) . '/var/galaxy.sqlite',
            self::env('DB_USER'),
            self::env('DB_PASSWORD'),
        );
    }

    private static function env(string $name): ?string
    {
        $value = getenv($name);

        return $value === false || $value === '' ? null : $value;
    }
}
