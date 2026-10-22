<?php

declare(strict_types=1);

/** @var string $base */
?>
<section class="container error-page">
    <p class="eyebrow">HTTP 404 / Route not found</p>
    <h1>No matching<br><em>action.</em></h1>
    <p class="intro">This URL doesn’t resolve to a public controller action.</p>
    <div class="actions"><a class="button" href="<?= htmlspecialchars($base) ?>/">Back to the demo ↗</a><a class="text-link" href="<?= htmlspecialchars($base) ?>/home/about#routes">How routes work ↗</a></div>
</section>
