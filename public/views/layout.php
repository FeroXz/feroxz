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
<?php
$userInitial = 'G';
if (!empty($currentUser['username'])) {
    $userInitial = strtoupper(substr($currentUser['username'], 0, 1));
    if (function_exists('mb_substr')) {
        $userInitial = mb_strtoupper(mb_substr($currentUser['username'], 0, 1, 'UTF-8'), 'UTF-8');
    }
}
?>
<header class="site-header">
    <div class="container site-header__inner">
        <div class="site-brand">
            <a class="logo" href="<?= url('home') ?>">Feroxz</a>
            <span class="tagline">Reptilienverwaltung &amp; Genetik</span>
        </div>
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
                <?php if (!empty($currentUser)): ?>
                    <li><a href="<?= url('account/animals') ?>">Meine Tiere</a></li>
                <?php endif; ?>
            </ul>
        </nav>
        <div class="header-actions">
            <button type="button" class="theme-toggle" id="theme-toggle" aria-pressed="false">🌙 Dark Mode</button>
            <?php if (!empty($currentUser)): ?>
                <details class="user-menu">
                    <summary>
                        <span class="avatar" aria-hidden="true"><?= htmlspecialchars($userInitial) ?></span>
                        <span class="user-name"><?= htmlspecialchars($currentUser['username']) ?></span>
                    </summary>
                    <div class="user-menu__panel">
                        <a href="<?= url('account/animals') ?>">Meine Tiere</a>
                        <?php if (userHasPermission($currentUser, 'animals') || ($currentUser['role'] ?? '') === 'admin'): ?>
                            <a href="<?= url('admin') ?>">Adminbereich</a>
                        <?php endif; ?>
                        <a href="<?= url('logout') ?>">Logout</a>
                    </div>
                </details>
            <?php else: ?>
                <a class="button subtle" href="<?= url('login') ?>">Login</a>
            <?php endif; ?>
        </div>
    </div>
</header>
<main class="container site-main">
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
