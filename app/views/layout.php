<?php

declare(strict_types=1);

/**
 * @var string $base
 * @var string $title
 * @var string $content
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="GalaxyMVC: a compact PHP framework with a reflection-checked router, prepared PDO queries, and plain PHP views. Explore the running demo and read the source.">
    <meta name="theme-color" content="#f4f1e8">
    <title><?= htmlspecialchars($title) ?><?= $title === 'GalaxyMVC' ? ' — A PHP MVC framework' : ' · GalaxyMVC' ?></title>
    <link rel="stylesheet" href="<?= htmlspecialchars($base) ?>/css/style.css">
</head>
<body>
<a class="skip-link" href="#main">Skip to content</a>
<header class="site-header">
    <nav class="container nav-row" aria-label="Main navigation">
        <a class="brand" href="<?= htmlspecialchars($base) ?>/" aria-label="GalaxyMVC home">
            <svg viewBox="0 0 240 240" width="36" height="36" aria-hidden="true"><path d="M195 47C141 7 49 40 32 112c-14 59 36 98 87 87-42-1-65-35-45-75 20-39 67-48 97-30-14-23-44-33-73-23 26-26 66-31 97-24Z" fill="#423078"/><path d="M45 193c54 40 146 7 163-65 14-59-36-98-87-87 42 1 65 35 45 75-20 39-67 48-97 30 14 23 44 33 73 23-26 26-66 31-97 24Z" fill="#8d75ca"/><circle cx="120" cy="120" r="15" fill="#edb44d"/></svg>
            <span>Galaxy<span class="brand-light">MVC</span></span>
        </a>
        <div class="nav-links">
            <a href="<?= htmlspecialchars($base) ?>/home/about">Guide</a>
            <a href="https://github.com/ZachariahAyers/GalaxyMVC">GitHub <span aria-hidden="true">↗</span></a>
        </div>
    </nav>
</header>
<main id="main">
<?= $content ?>
</main>
<footer class="site-footer">
    <div class="container footer-row">
        <a href="https://ayers.repair">Ayers Software Repair <span aria-hidden="true">↗</span></a>
        <span>Built with PHP. Served by GalaxyMVC.</span>
        <a href="https://github.com/ZachariahAyers/GalaxyMVC/blob/master/LICENSE">Apache 2.0</a>
    </div>
</footer>
</body>
</html>
