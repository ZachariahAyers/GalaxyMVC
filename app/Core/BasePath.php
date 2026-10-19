<?php

declare(strict_types=1);

namespace App\Core;

/**
 * The path prefix the app is served under, read from BASE_PATH: `/opensource/galaxymvc`,
 * or empty when the app is served from the root. The front controller strips it before routing,
 * and the views put it in front of every link.
 */
final readonly class BasePath
{
    public string $prefix;

    public function __construct(string $value)
    {
        $trimmed = trim($value, '/');
        $this->prefix = $trimmed === '' ? '' : '/' . $trimmed;
    }

    public static function fromEnvironment(): self
    {
        $value = getenv('BASE_PATH');

        return new self($value === false ? '' : $value);
    }

    /**
     * The path inside the app, or null when the request is outside the base path.
     */
    public function strip(string $path): ?string
    {
        if ($this->prefix === '') {
            return $path;
        }
        if ($path === $this->prefix) {
            return '/';
        }

        return str_starts_with($path, $this->prefix . '/') ? substr($path, strlen($this->prefix)) : null;
    }
}
