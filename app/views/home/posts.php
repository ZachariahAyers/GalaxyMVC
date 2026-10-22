<?php

declare(strict_types=1);

/** @var list<array<string, mixed>> $posts */
?>
<div class="query-results" aria-label="Records rendered from the database">
    <?php foreach ($posts as $post) : ?>
        <article class="result-row">
            <span class="row-number">#<?= htmlspecialchars((string) $post['id']) ?></span>
            <h3><?= htmlspecialchars((string) $post['title']) ?></h3>
            <p><?= htmlspecialchars((string) $post['content']) ?></p>
        </article>
    <?php endforeach; ?>
    <?php if ($posts === []) : ?>
        <p class="empty-state">No posts in the database. Run <code>composer setup</code> in a fresh local checkout to load the sample records.</p>
    <?php endif; ?>
</div>
