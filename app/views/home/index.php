<?php
/**
 * @var list<array<string, mixed>> $posts
 */
?>
<section class="hero">
    <div class="container">
        <h1 class="hero-title">GalaxyMVC</h1>
        <p class="hero-subtitle">A small PHP MVC framework built from scratch</p>
        <p class="hero-description">A front controller, a URL router, a PDO data layer and plain PHP views, small enough to read in one sitting.</p>
        <a class="patch-seal" href="https://www.php.net/archive/2026.php#2026-10-21-5" aria-label="PHP2Shell patched: PHP security advisory">
            <span>PHP2Shell</span>
            <strong>Patched</strong>
            <span>21 Oct 2026</span>
        </a>
    </div>
</section>

<section class="architecture-section">
    <div class="container">
        <h2 class="section-title">How a request flows</h2>
        <div class="mvc-diagram">
            <div class="mvc-box router">
                <h3>Router</h3>
                <p>Maps <code>/controller/method/params</code> to a public controller action</p>
                <code>app/Core/Router.php</code>
            </div>
            <div class="mvc-box controller">
                <h3>Controller</h3>
                <p>Asks the model for data and picks a view</p>
                <code>app/Controllers/</code>
            </div>
            <div class="mvc-box model">
                <h3>Model</h3>
                <p>Runs prepared statements through the PDO wrapper</p>
                <code>app/Models/</code>
            </div>
            <div class="mvc-box view">
                <h3>View</h3>
                <p>Plain PHP templates rendered inside one layout</p>
                <code>app/views/</code>
            </div>
        </div>
    </div>
</section>

<section class="posts-section">
    <div class="container">
        <h2 class="section-title">Posts</h2>
        <p class="section-note">Loaded from the <code>posts</code> table by <code>Post::all()</code>.</p>
        <?php foreach ($posts as $post) : ?>
            <article class="post-card">
                <h3><?= htmlspecialchars((string) $post['title']) ?></h3>
                <p><?= htmlspecialchars((string) $post['content']) ?></p>
            </article>
        <?php endforeach; ?>
    </div>
</section>
