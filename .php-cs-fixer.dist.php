<?php

declare(strict_types=1);

$finder = (new PhpCsFixer\Finder())
    ->in([__DIR__ . '/app', __DIR__ . '/database', __DIR__ . '/public', __DIR__ . '/tests'])
    // Templates mix HTML and PHP; PSR-12 covers PHP-only files.
    ->exclude('views')
    ->append([__FILE__]);

return (new PhpCsFixer\Config())
    ->setRules(['@PSR12' => true])
    ->setFinder($finder);
