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
<header class="admin-header">
    <div class="container">
        <h1>Feroxz Admin</h1>
        <div class="header-controls">
            <nav>
                <a href="<?= url('admin') ?>">Dashboard</a>
                <a href="<?= url('admin/posts') ?>">Beiträge</a>
                <a href="<?= url('admin/pages') ?>">Seiten</a>
                <a href="<?= url('admin/gallery') ?>">Galerie</a>
                <a href="<?= url('admin/genetics') ?>">Genetik</a>
                <a href="<?= url('home') ?>" target="_blank">Zur Website</a>
                <a href="<?= url('logout') ?>">Logout (<?= htmlspecialchars($_SESSION['username'] ?? 'Admin') ?>)</a>
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
</body>
</html>
