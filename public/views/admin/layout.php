<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feroxz Admin</title>
    <link rel="stylesheet" href="<?= asset('styles.css') ?>">
    <script defer src="<?= asset('theme.js') ?>"></script>
</head>
<body class="admin">
<?php
$adminRoute = $_GET['route'] ?? '';
$adminInitial = 'A';
if (!empty($currentUser['username'])) {
    $adminInitial = strtoupper(substr($currentUser['username'], 0, 1));
    if (function_exists('mb_substr')) {
        $adminInitial = mb_strtoupper(mb_substr($currentUser['username'], 0, 1, 'UTF-8'), 'UTF-8');
    }
}
?>
<header class="admin-header">
    <div class="container admin-header__inner">
        <div class="admin-brand">
            <a href="<?= url('admin') ?>">Feroxz Admin</a>
            <span class="tagline">Kontrollzentrum</span>
        </div>
        <nav class="admin-nav">
            <?php foreach ($navItems as $item): ?>
                <a href="<?= url($item['route']) ?>" class="<?= $adminRoute === $item['route'] ? 'active' : '' ?>"><?= htmlspecialchars($item['label']) ?></a>
            <?php endforeach; ?>
        </nav>
        <div class="admin-actions">
            <a class="button subtle" href="<?= url('home') ?>" target="_blank" rel="noopener">Website öffnen</a>
            <button type="button" class="theme-toggle" id="theme-toggle" aria-pressed="false">🌙 Dark Mode</button>
            <details class="user-menu">
                <summary>
                    <span class="avatar" aria-hidden="true"><?= htmlspecialchars($adminInitial) ?></span>
                    <span class="user-name"><?= htmlspecialchars($currentUser['username'] ?? 'Admin') ?></span>
                </summary>
                <div class="user-menu__panel">
                    <a href="<?= url('account/animals') ?>">Meine Tiere</a>
                    <a href="<?= url('logout') ?>">Logout</a>
                </div>
            </details>
        </div>
    </div>
</header>
<main class="container admin-main">
    <?php if ($flash = getFlash()): ?>
        <div class="flash flash-<?= htmlspecialchars($flash['type']) ?>">
            <?= htmlspecialchars($flash['message']) ?>
        </div>
    <?php endif; ?>
    <?= $content ?>
</main>
</body>
</html>
