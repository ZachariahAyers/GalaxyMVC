<?php

declare(strict_types=1);

/** @var string $base */
?>
<div class="container guide-header"><p class="eyebrow">The GalaxyMVC guide</p><h1>Start with<br><em>a request.</em></h1><p class="intro">Run the framework locally, then follow the PHP from the front controller to the rendered page.</p></div>
<div class="container guide-grid">
    <nav class="guide-nav" aria-label="On this page"><a href="#start">01 / Run locally</a><a href="#routes">02 / Route a request</a><a href="#data">03 / Query with PDO</a><a href="#views">04 / Render a view</a><a href="#checks">05 / Run the checks</a></nav>
    <div class="guide-content">
        <section id="start"><p class="eyebrow">01 / Get started</p><h2>Run it on your machine.</h2><p>Use PHP 8.4.27+ or 8.5.12+ in those branches, Composer, and <code>pdo_sqlite</code>. In a fresh checkout:</p>
<pre tabindex="0" aria-label="Local setup commands"><code>git clone https://github.com/ZachariahAyers/GalaxyMVC.git
cd GalaxyMVC
composer install
composer setup
php -S localhost:8000 -t public public/index.php</code></pre>
        <p>Open <code>http://localhost:8000</code>. The setup command creates and seeds the sample database. Running it again replaces the sample posts.</p></section>
        <section id="routes"><p class="eyebrow">02 / Routing</p><h2>A URL names an action.</h2><p><code>/home/about</code> resolves to <code>Home::about()</code>. The router uses reflection to check that the action is public, non-static, and accepts the supplied number of arguments. An unmatched route returns HTTP 404.</p>
<pre tabindex="0" aria-label="PHP about controller example"><code><span class="syntax-keyword">public function</span> <span class="syntax-function">about</span>(): <span class="syntax-type">string</span>
{
    <span class="syntax-keyword">return</span> <span class="syntax-variable">$this</span>-&gt;<span class="syntax-function">render</span>(<span class="syntax-string">'home/about'</span>, [<span class="syntax-string">'title'</span> =&gt; <span class="syntax-string">'Guide'</span>]);
}</code></pre>
        <p>That is the action serving this guide. Keep controller helpers <code>protected</code> or <code>private</code>; public methods are routable.</p><a class="text-link" href="https://github.com/ZachariahAyers/GalaxyMVC/blob/master/app/Core/Router.php">Read Router.php ↗</a></section>
        <section id="data"><p class="eyebrow">03 / Data</p><h2>Write SQL. Bind the values.</h2><p>The database wrapper prepares each statement and binds values with their PDO types. Use positional or named parameters.</p>
<pre tabindex="0" aria-label="Parameterized PDO query example"><code><span class="syntax-variable">$post</span> = <span class="syntax-variable">$this</span>-&gt;db-&gt;<span class="syntax-function">first</span>(
    <span class="syntax-string">'SELECT id, title FROM posts WHERE id = :id'</span>,
    [<span class="syntax-string">'id'</span> =&gt; <span class="syntax-number">1</span>],
);</code></pre>
        <p><code>select()</code> returns rows, <code>first()</code> returns one row or <code>null</code>, and <code>execute()</code> returns the affected row count. Set <code>DB_DSN</code>, <code>DB_USER</code>, and <code>DB_PASSWORD</code> for your database.</p><a class="text-link" href="https://github.com/ZachariahAyers/GalaxyMVC/blob/master/app/Core/Database.php">Read Database.php ↗</a></section>
        <section id="views"><p class="eyebrow">04 / Views</p><h2>Return HTML from PHP.</h2><p><code>render()</code> passes data to a template in <code>app/views</code> and wraps it in the shared layout. Escape values when writing them into HTML.</p>
<pre tabindex="0" aria-label="Escaped PHP view example"><code>&lt;h1&gt;&lt;?= <span class="syntax-function">htmlspecialchars</span>(<span class="syntax-variable">$title</span>) ?&gt;&lt;/h1&gt;</code></pre>
        <p>Use <code>$base</code> for internal links so the same application works at the domain root or under a path such as this demo’s <code>/opensource/galaxymvc</code>.</p><a class="text-link" href="https://github.com/ZachariahAyers/GalaxyMVC/blob/master/app/Core/View.php">Read View.php ↗</a></section>
        <section id="checks"><p class="eyebrow">05 / Development</p><h2>Check the whole framework.</h2><pre tabindex="0" aria-label="Run project checks"><code>composer check</code></pre><p>Runs PHP syntax checks, PSR-12 formatting checks, PHPStan at level 10, and PHPUnit. The full architecture notes live with the source.</p><a class="text-link" href="https://github.com/ZachariahAyers/GalaxyMVC/blob/master/docs/ARCHITECTURE.md">Read the architecture ↗</a></section>
    </div>
</div>
