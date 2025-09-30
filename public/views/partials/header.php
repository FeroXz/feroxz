<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($settings['site_title'] ?? APP_NAME) ?></title>
    <link rel="stylesheet" href="<?= asset('style.css') ?>">
</head>
<body>
<header>
    <div class="wrapper">
        <nav class="navbar">
            <div>
                <strong><?= htmlspecialchars($settings['site_title'] ?? APP_NAME) ?></strong>
                <div style="font-size:0.85rem;color:var(--text-muted);margin-top:0.25rem;">
                    <?= htmlspecialchars($settings['site_tagline'] ?? '') ?>
                </div>
            </div>
            <div class="nav-links">
                <a href="<?= BASE_URL ?>/index.php" class="<?= ($currentRoute === 'home') ? 'active' : '' ?>">Start</a>
                <a href="<?= BASE_URL ?>/index.php?route=animals" class="<?= ($currentRoute === 'animals') ? 'active' : '' ?>">Tierübersicht</a>
                <a href="<?= BASE_URL ?>/index.php?route=news" class="<?= ($currentRoute === 'news') ? 'active' : '' ?>">Neuigkeiten</a>
                <a href="<?= BASE_URL ?>/index.php?route=care-guide" class="<?= ($currentRoute === 'care-guide' || $currentRoute === 'care-article') ? 'active' : '' ?>">Pflegeleitfaden</a>
                <?php foreach (($navCareArticles ?? []) as $careNav): ?>
                    <a href="<?= BASE_URL ?>/index.php?route=care-article&amp;slug=<?= urlencode($careNav['slug']) ?>" class="<?= ($currentRoute === 'care-article' && ($activeCareSlug ?? '') === $careNav['slug']) ? 'active' : '' ?>"><?= htmlspecialchars($careNav['title']) ?></a>
                <?php endforeach; ?>
                <?php foreach (($navPages ?? []) as $navPage): ?>
                    <a href="<?= BASE_URL ?>/index.php?route=page&amp;slug=<?= urlencode($navPage['slug']) ?>" class="<?= ($currentRoute === 'page' && ($activePageSlug ?? '') === $navPage['slug']) ? 'active' : '' ?>"><?= htmlspecialchars($navPage['title']) ?></a>
                <?php endforeach; ?>
                <a href="<?= BASE_URL ?>/index.php?route=adoption" class="<?= ($currentRoute === 'adoption') ? 'active' : '' ?>">Tierabgabe</a>
                <?php if (current_user()): ?>
                    <a href="<?= BASE_URL ?>/index.php?route=my-animals" class="<?= ($currentRoute === 'my-animals') ? 'active' : '' ?>">Meine Tiere</a>
                    <a href="<?= BASE_URL ?>/index.php?route=breeding" class="<?= ($currentRoute === 'breeding') ? 'active' : '' ?>">Zuchtplanung</a>
                    <a href="<?= BASE_URL ?>/index.php?route=admin/dashboard" class="<?= str_starts_with($currentRoute, 'admin/') ? 'active' : '' ?>">Admin</a>
                    <a href="<?= BASE_URL ?>/index.php?route=logout">Logout</a>
                <?php else: ?>
                    <a href="<?= BASE_URL ?>/index.php?route=login" class="<?= ($currentRoute === 'login') ? 'active' : '' ?>">Login</a>
                <?php endif; ?>
            </div>
        </nav>
    </div>
</header>
<main>
    <div class="wrapper">
