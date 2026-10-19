<?php

declare(strict_types=1);

namespace App\Core;

use RuntimeException;
use Throwable;

/**
 * Renders a plain PHP template from app/views inside the shared layout.
 */
final class View
{
    private const DIRECTORY = __DIR__ . '/../views/';

    /**
     * @param array<string, mixed> $data variables made available to the template
     */
    public static function render(string $view, array $data = []): string
    {
        $content = self::capture($view, $data);

        return self::capture('layout', ['content' => $content] + $data);
    }

    /**
     * @param array<string, mixed> $data
     */
    private static function capture(string $view, array $data): string
    {
        $template = self::DIRECTORY . $view . '.php';
        if (!is_file($template)) {
            throw new RuntimeException("View not found: {$view}");
        }

        extract($data, EXTR_SKIP);
        ob_start();
        try {
            require $template;
        } catch (Throwable $e) {
            ob_end_clean();
            throw $e;
        }

        return (string) ob_get_clean();
    }
}
