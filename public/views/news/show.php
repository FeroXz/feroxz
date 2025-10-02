<?php include __DIR__ . '/../partials/header.php'; ?>
<section class="section">
    <div class="section__inner">
        <?php if (!empty($pageMeta['breadcrumbs'])): ?>
            <nav class="breadcrumb" aria-label="Brotkrumen">
                <?php foreach ($pageMeta['breadcrumbs'] as $crumb): ?>
                    <a href="<?= htmlspecialchars($crumb['url']) ?>"><?= htmlspecialchars($crumb['name']) ?></a>
                    <span aria-hidden="true">/</span>
                <?php endforeach; ?>
                <span><?= htmlspecialchars($post['title']) ?></span>
            </nav>
        <?php endif; ?>
        <article class="article-shell">
            <header>
                <time datetime="<?= htmlspecialchars(date('Y-m-d', strtotime($post['published_at'] ?? 'now'))) ?>">Veröffentlicht am <?= date('d.m.Y', strtotime($post['published_at'] ?? 'now')) ?></time>
                <h1><?= htmlspecialchars($post['title']) ?></h1>
            </header>
            <?php if (!empty($post['hero_image'])): ?>
                <figure>
                    <?= render_responsive_picture($post['hero_image'], $post['title'], [
                        'sizes' => '(max-width: 768px) 100vw, 960px',
                    ]) ?>
                </figure>
            <?php endif; ?>
            <div class="content-prose">
                <?= render_rich_text($post['content']) ?>
            </div>
        </article>
    </div>
</section>
<?php include __DIR__ . '/../partials/footer.php'; ?>
