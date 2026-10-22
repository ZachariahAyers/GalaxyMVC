<?php

declare(strict_types=1);

/**
 * @var string $base
 * @var list<array<string, mixed>> $posts
 * @var array<string, string> $sources
 */
?>
<section class="container hero">
    <div class="edition"><span>A small framework for PHP</span><span>Model / View / Controller</span></div>
    <h1 class="masthead">Galaxy<span>MVC</span><span class="masthead-dot" aria-hidden="true">.</span></h1>
    <div class="hero-grid">
        <div class="php-print" aria-hidden="true">
            <span class="print-registration">PHP / HYPERTEXT PREPROCESSOR</span>
            <span class="php-glyph">&lt;?php</span>
            <span class="print-baseline"><span>Request in.</span><span>HTML out.</span></span>
        </div>
        <div class="hero-copy">
            <a class="patch-seal" href="https://www.php.net/archive/2026.php#2026-10-21-5" aria-label="PHP2Shell patched: PHP security advisory"><span>PHP2Shell</span><strong>Patched</strong></a>
            <h2>Small by design.<br>PHP all the<br>way down.</h2>
            <p>A router, a PDO data layer, and plain PHP views. Enough structure to build with. Little enough to read in full.</p>
            <div class="actions"><a class="button" href="<?= htmlspecialchars($base) ?>/home/about#start">Get started <span aria-hidden="true">↗</span></a><a class="text-link" href="#demo">Explore the demo <span aria-hidden="true">↓</span></a></div>
        </div>
    </div>
    <div class="facts"><span><strong>PHP</strong> 8.4.27+ / 8.5.12+</span><span><strong>Data</strong> Prepared PDO queries</span><span><strong>Views</strong> Plain PHP</span><span><strong>License</strong> Apache 2.0</span></div>
</section>
<section class="container live-section" id="demo">
    <div class="section-heading"><p class="eyebrow">01 / In practice</p><h2>A page with<br>nothing to hide.</h2><p>These posts come from the demo’s SQLite database. Open the source below to see the controller, model, and view that put them here.</p></div>
    <div class="demo-output">
        <div class="output-heading"><span><span class="status-dot" aria-hidden="true"></span>Rendered by GalaxyMVC</span><code>GET /home/index</code></div>
        <?php require __DIR__ . '/posts.php'; ?>
    </div>
    <div class="source-list">
        <?php foreach ($sources as $path => $source) : ?>
            <details class="source-file">
                <summary><span class="source-label">View source</span><span class="source-path"><?= htmlspecialchars($path) ?></span><span class="disclosure" aria-hidden="true"></span></summary>
                <pre tabindex="0" aria-label="<?= htmlspecialchars($path) ?> source code"><code><?= htmlspecialchars($source) ?></code></pre>
                <a class="text-link source-link" href="https://github.com/ZachariahAyers/GalaxyMVC/blob/master/<?= htmlspecialchars($path) ?>">Open on GitHub <span aria-hidden="true">↗</span></a>
            </details>
        <?php endforeach; ?>
    </div>
</section>
<section class="container reading-strip">
    <p class="eyebrow">02 / Make it yours</p>
    <h2>Your next request<br>starts here<span aria-hidden="true">_</span></h2>
    <div><p>Install it. Follow a request.<br>Build from there.</p><a class="button" href="<?= htmlspecialchars($base) ?>/home/about">Read the guide <span aria-hidden="true">↗</span></a></div>
</section>
