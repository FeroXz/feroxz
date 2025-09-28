<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feroxz CMS</title>
    <link rel="stylesheet" href="<?= asset('styles.css') ?>">
    <script defer src="<?= asset('theme.js') ?>"></script>
</head>
<body>
<header class="site-header">
    <div class="container">
        <h1 class="logo"><a href="<?= url('home') ?>">Feroxz CMS</a></h1>
        <div class="header-controls">
            <?php if (!function_exists('renderPageMenuItems')): ?>
                <?php
                function renderPageMenuItems(array $items): void
                {
                    foreach ($items as $item) {
                        $hasChildren = !empty($item['children']);
                        echo '<li>';
                        echo '<a href="' . url('page', ['slug' => $item['slug']]) . '">' . htmlspecialchars($item['title']) . '</a>';
                        if ($hasChildren) {
                            echo '<ul>';
                            renderPageMenuItems($item['children']);
                            echo '</ul>';
                        }
                        echo '</li>';
                    }
                }
                ?>
            <?php endif; ?>
            <nav class="main-nav">
                <ul class="main-nav__root">
                    <li><a href="<?= url('home') ?>">Start</a></li>
                    <li><a href="<?= url('animals') ?>">Tiere</a></li>
                    <li><a href="<?= url('gallery') ?>">Galerie</a></li>
                    <li><a href="<?= url('genetics') ?>">Genetik</a></li>
                    <?php renderPageMenuItems($pages); ?>
                    <li class="main-nav__admin"><a href="<?= url('login') ?>" class="login-link">Admin</a></li>
                </ul>
            </nav>
            <button type="button" class="theme-toggle" id="theme-toggle" aria-pressed="false">🌙 Dark Mode</button>
        </div>
    </div>
</header>
<main class="container">
    <?php if ($flash = getFlash()): ?>
        <div class="flash flash-<?= htmlspecialchars($flash['type']) ?>">
            <?= htmlspecialchars($flash['message']) ?>
        </div>
    <?php endif; ?>
    <?= $content ?>
</main>
<footer class="site-footer">
    <div class="container">
        <p>&copy; <?= date('Y') ?> Feroxz CMS. Inhalte werden serverseitig in SQLite gespeichert.</p>
    </div>
</footer>
</body>
</html>
