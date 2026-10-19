<?php
/**
 * @var string $title
 * @var string $content
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($title) ?></title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
<nav class="site-nav">
    <div class="container">
        <a href="/">GalaxyMVC</a>
        <a href="/home/about">About</a>
    </div>
</nav>
<main>
<?= $content ?>
</main>
<footer class="site-footer">
    <div class="container">
        <p>Built by <a href="https://ayers.repair">Zachariah Ayers</a>.</p>
    </div>
</footer>
</body>
</html>
