<!DOCTYPE html>
<html lang="<?= htmlspecialchars($pageMeta['lang'] ?? 'de') ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageMeta['full_title'] ?? ($settings['site_title'] ?? SITE_NAME)) ?></title>
    <link rel="canonical" href="<?= htmlspecialchars($pageMeta['canonical'] ?? canonical_url()) ?>">
    <meta name="description" content="<?= htmlspecialchars($pageMeta['description'] ?? '') ?>">
    <meta name="keywords" content="<?= htmlspecialchars(PRIMARY_TOPIC) ?>">

    <meta name="theme-color" content="#e0e7ff">

    <meta property="og:site_name" content="<?= htmlspecialchars(SITE_NAME) ?>">
    <meta property="og:title" content="<?= htmlspecialchars($pageMeta['og_title'] ?? '') ?>">
    <meta property="og:description" content="<?= htmlspecialchars($pageMeta['og_description'] ?? '') ?>">
    <meta property="og:url" content="<?= htmlspecialchars($pageMeta['canonical'] ?? canonical_url()) ?>">
    <meta property="og:type" content="<?= htmlspecialchars($pageMeta['og_type'] ?? 'website') ?>">
    <meta property="og:image" content="<?= htmlspecialchars($pageMeta['og_image'] ?? ORG_LOGO_URL) ?>">
    <meta property="og:image:alt" content="<?= htmlspecialchars($pageMeta['og_image_alt'] ?? SITE_NAME) ?>">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= htmlspecialchars($pageMeta['og_title'] ?? '') ?>">
    <meta name="twitter:description" content="<?= htmlspecialchars($pageMeta['og_description'] ?? '') ?>">
    <meta name="twitter:image" content="<?= htmlspecialchars($pageMeta['og_image'] ?? ORG_LOGO_URL) ?>">
    <link rel="alternate" hreflang="de" href="<?= htmlspecialchars($pageMeta['canonical'] ?? canonical_url()) ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="<?= asset('style.css') ?>">
</head>
<body>
<a class="skip-link" href="#main">Zum Inhalt springen</a>
<header class="site-header">
    <div class="site-header__bar">
        <a href="<?= BASE_URL ?>/index.php" class="site-header__brand">
            <span class="site-header__brand-logo">
                <?= strtoupper(substr($settings['site_title'] ?? APP_NAME, 0, 2)) ?>
            </span>
            <span class="site-header__brand-copy">
                <strong><?= htmlspecialchars($settings['site_title'] ?? APP_NAME) ?></strong>
                <span><?= htmlspecialchars($settings['site_tagline'] ?? 'FeroxZ Reptile Center') ?></span>
            </span>
        </a>
        <button type="button" class="site-header__toggle" data-nav-toggle aria-expanded="false" aria-controls="primary-navigation">
            <span class="sr-only">Navigation öffnen</span>
            <svg width="22" height="16" viewBox="0 0 22 16" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <path d="M1 1h20M1 8h20M1 15h20" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
            </svg>
        </button>
        <?php
            $linkBase = 'site-nav__link';
            $linkActive = 'site-nav__link site-nav__link--active';
        ?>
        <nav id="primary-navigation" class="site-nav" data-nav aria-label="Hauptnavigation">
            <a href="<?= BASE_URL ?>/index.php" class="<?= ($currentRoute === 'home') ? $linkActive : $linkBase ?>">Start</a>
            <?php $isCareActive = ($currentRoute === 'care-guide' || $currentRoute === 'care-article'); ?>
            <div class="site-nav__group" data-nav-group>
                <button type="button" class="<?= $isCareActive ? $linkActive : $linkBase ?>" data-nav-trigger aria-expanded="false">
                    Pflegeleitfaden
                    <svg width="16" height="10" viewBox="0 0 16 10" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <path d="M1 1l7 7 7-7" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </button>
                <div class="site-nav__dropdown" role="menu">
                    <a href="<?= BASE_URL ?>/index.php?route=care-guide" class="site-nav__dropdown-link" role="menuitem">Übersicht</a>
                    <?php foreach (($navCareArticles ?? []) as $careNav): ?>
                        <a href="<?= BASE_URL ?>/index.php?route=care-article&amp;slug=<?= urlencode($careNav['slug']) ?>" class="site-nav__dropdown-link" role="menuitem">
                            <?= htmlspecialchars($careNav['title']) ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
            <a href="<?= BASE_URL ?>/index.php?route=genetics" class="<?= ($currentRoute === 'genetics') ? $linkActive : $linkBase ?>">Genetik-Rechner</a>
            <a href="<?= BASE_URL ?>/index.php?route=animals" class="<?= ($currentRoute === 'animals') ? $linkActive : $linkBase ?>">Tierübersicht</a>
            <a href="<?= BASE_URL ?>/index.php?route=adoption" class="<?= ($currentRoute === 'adoption') ? $linkActive : $linkBase ?>">Tierabgabe</a>
            <a href="<?= BASE_URL ?>/index.php?route=news" class="<?= ($currentRoute === 'news') ? $linkActive : $linkBase ?>">Neuigkeiten</a>
            <?php foreach (($navPages ?? []) as $navPage): ?>
                <?php
                    $parentActive = ($currentRoute === 'page' && ($activePageSlug ?? '') === $navPage['slug']);
                    $childActive = false;
                    foreach ($navPage['children'] ?? [] as $childPage) {
                        if ($currentRoute === 'page' && ($activePageSlug ?? '') === $childPage['slug']) {
                            $childActive = true;
                            break;
                        }
                    }
                    $isActive = $parentActive || $childActive;
                ?>
                <div class="site-nav__group" data-nav-group>
                    <button type="button" class="<?= $isActive ? $linkActive : $linkBase ?>" data-nav-trigger aria-expanded="false">
                        <?= htmlspecialchars($navPage['title']) ?>
                        <?php if (!empty($navPage['children'])): ?>
                            <svg width="16" height="10" viewBox="0 0 16 10" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                <path d="M1 1l7 7 7-7" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        <?php endif; ?>
                    </button>
                    <div class="site-nav__dropdown" role="menu">
                        <a href="<?= BASE_URL ?>/index.php?route=page&amp;slug=<?= urlencode($navPage['slug']) ?>" class="site-nav__dropdown-link" role="menuitem">
                            <?= htmlspecialchars($navPage['title']) ?> Übersicht
                        </a>
                        <?php foreach ($navPage['children'] ?? [] as $childPage): ?>
                            <a href="<?= BASE_URL ?>/index.php?route=page&amp;slug=<?= urlencode($childPage['slug']) ?>" class="site-nav__dropdown-link" role="menuitem">
                                <?= htmlspecialchars($childPage['title']) ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
            <a href="<?= BASE_URL ?>/index.php?route=page&amp;slug=ueber-uns" class="<?= ($currentRoute === 'page' && ($activePageSlug ?? '') === 'ueber-uns') ? $linkActive : $linkBase ?>">Über uns</a>
            <a href="<?= BASE_URL ?>/index.php?route=page&amp;slug=kontakt" class="<?= ($currentRoute === 'page' && ($activePageSlug ?? '') === 'kontakt') ? $linkActive : $linkBase ?>">Kontakt</a>
            <?php if (current_user()): ?>
                <a href="<?= BASE_URL ?>/index.php?route=my-animals" class="<?= ($currentRoute === 'my-animals') ? $linkActive : $linkBase ?>">Meine Tiere</a>
                <a href="<?= BASE_URL ?>/index.php?route=breeding" class="<?= ($currentRoute === 'breeding') ? $linkActive : $linkBase ?>">Zuchtplanung</a>
                <a href="<?= BASE_URL ?>/index.php?route=admin/dashboard" class="<?= str_starts_with($currentRoute, 'admin/') ? $linkActive : $linkBase ?>">Admin</a>
                <a href="<?= BASE_URL ?>/index.php?route=logout" class="<?= $linkBase ?>">Logout</a>
            <?php else: ?>
                <a href="<?= BASE_URL ?>/index.php?route=login" class="<?= ($currentRoute === 'login') ? $linkActive : $linkBase ?>">Login</a>
            <?php endif; ?>
        </nav>
    </div>
</header>
<main id="main">
