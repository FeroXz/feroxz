<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars(($settings['site_title'] ?? APP_NAME) . ' – ' . ($settings['site_tagline'] ?? 'Reptile CMS')) ?></title>
    <link rel="stylesheet" href="<?= asset('style.css') ?>">
</head>
<body>
<header>
    <div class="wrapper">
        <nav class="navbar">
            <div>
                <a href="<?= route_url('home') ?>" style="color:var(--text);text-decoration:none;">
                    <strong style="font-size:1.35rem;letter-spacing:0.05em;"><?= htmlspecialchars($settings['site_title'] ?? APP_NAME) ?></strong>
                </a>
                <div style="font-size:0.85rem;color:var(--text-muted);margin-top:0.35rem;max-width:320px;">
                    <?= htmlspecialchars($settings['site_tagline'] ?? '') ?>
                </div>
            </div>
            <div class="nav-links">
                <?php foreach (($menuItems ?? []) as $item): ?>
                    <?php
                        $url = '#';
                        if (!empty($item['route'])) {
                            $url = route_url($item['route']);
                        } elseif (!empty($item['page_slug'])) {
                            $url = route_url('page', ['slug' => $item['page_slug']]);
                        } elseif (!empty($item['external_url'])) {
                            $url = $item['external_url'];
                        }
                        $isActive = false;
                        if (!empty($item['route']) && $currentRoute === $item['route']) {
                            $isActive = true;
                        }
                        if (!empty($item['route']) && str_starts_with($currentRoute, $item['route'] . '/')) {
                            $isActive = true;
                        }
                        if (!empty($item['page_slug']) && ($currentRoute === 'page') && ($_GET['slug'] ?? '') === $item['page_slug']) {
                            $isActive = true;
                        }
                    ?>
                    <a href="<?= htmlspecialchars($url) ?>" class="<?= $isActive ? 'active' : '' ?>">
                        <?= htmlspecialchars($item['label']) ?>
                    </a>
                <?php endforeach; ?>
                <?php if (!empty($currentUser)): ?>
                    <a href="<?= route_url('my-animals') ?>" class="<?= $currentRoute === 'my-animals' ? 'active' : '' ?>">Meine Tiere</a>
                    <a href="<?= route_url('admin/dashboard') ?>" class="<?= str_starts_with($currentRoute, 'admin/') ? 'active' : '' ?>">Admin</a>
                    <a href="<?= route_url('logout') ?>">Logout</a>
                <?php else: ?>
                    <a href="<?= route_url('login') ?>" class="<?= $currentRoute === 'login' ? 'active' : '' ?>">Login</a>
                <?php endif; ?>
            </div>
        </nav>
    </div>
</header>
<main>
    <div class="wrapper">
