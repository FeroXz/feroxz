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
            <nav class="main-nav">
                <a href="<?= url('home') ?>">Start</a>
                <a href="<?= url('gallery') ?>">Galerie</a>
                <a href="<?= url('genetics') ?>">Genetik</a>
                <?php foreach ($pages as $navPage): ?>
                    <a href="<?= url('page', ['slug' => $navPage['slug']]) ?>"><?= htmlspecialchars($navPage['title']) ?></a>
                <?php endforeach; ?>
                <a href="<?= url('login') ?>" class="login-link">Admin</a>
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
