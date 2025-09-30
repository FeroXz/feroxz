<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php
    $siteTitle = settingValue($settings, 'site_title', 'Feroxz');
    $accountTitle = !empty($title) ? $title . ' – ' . $siteTitle : 'Mein Tierbereich – ' . $siteTitle;
    ?>
    <title><?= htmlspecialchars($accountTitle) ?></title>
    <link rel="stylesheet" href="<?= asset('styles.css') ?>">
    <script defer src="<?= asset('theme.js') ?>"></script>
</head>
<body class="account">
<?php
$initial = 'U';
if (!empty($currentUser['username'])) {
    $initial = strtoupper(substr($currentUser['username'], 0, 1));
    if (function_exists('mb_substr')) {
        $initial = mb_strtoupper(mb_substr($currentUser['username'], 0, 1, 'UTF-8'), 'UTF-8');
    }
}
$currentRoute = $_GET['route'] ?? '';
?>
<header class="account-header">
    <div class="container account-header__inner">
        <div class="account-brand">
            <a class="logo" href="<?= url('home') ?>"><?= htmlspecialchars($siteTitle) ?></a>
            <span class="tagline">Persönliche Tierverwaltung</span>
        </div>
        <button type="button" class="nav-toggle" data-target="account-primary-nav" aria-expanded="false" aria-controls="account-primary-nav">
            <span class="sr-only">Account-Navigation umschalten</span>
            <span class="nav-toggle__icon" aria-hidden="true">
                <span></span>
                <span></span>
                <span></span>
            </span>
        </button>
        <div class="header-collapsible" id="account-primary-nav">
            <nav class="account-nav">
                <a href="<?= url('account/animals') ?>" class="<?= $currentRoute === 'account/animals' ? 'active' : '' ?>">Meine Tiere</a>
                <?php if (userHasPermission($currentUser, 'animals') || ($currentUser['role'] ?? '') === 'admin'): ?>
                    <a href="<?= url('admin') ?>" class="<?= str_starts_with($currentRoute, 'admin') ? 'active' : '' ?>">Adminbereich</a>
                <?php endif; ?>
                <a href="<?= url('logout') ?>">Logout</a>
            </nav>
            <div class="account-actions">
                <button type="button" class="theme-toggle" id="theme-toggle" aria-pressed="false">🌙 Dark Mode</button>
                <div class="account-user">
                    <span class="avatar" aria-hidden="true"><?= htmlspecialchars($initial) ?></span>
                    <div class="user-meta">
                        <strong><?= htmlspecialchars($currentUser['username'] ?? 'User') ?></strong>
                        <small><?= htmlspecialchars(($currentUser['role'] ?? 'user') === 'admin' ? 'Administrator' : 'Benutzer') ?></small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
<main class="container account-main">
    <?php if ($flash = getFlash()): ?>
        <div class="flash flash-<?= htmlspecialchars($flash['type']) ?>">
            <?= htmlspecialchars($flash['message']) ?>
        </div>
    <?php endif; ?>
    <?= $content ?>
</main>
</body>
</html>
