<?php include __DIR__ . '/../partials/header.php'; ?>
<section class="section">
    <div class="section__inner">
        <header class="section-header">
            <h1 class="section-header__title">Pflegeleitfaden</h1>
            <p class="section-header__description">Wissensdatenbank mit Pflegeprofilen, Technik- und Ernährungsrichtlinien für Bartagamen.</p>
        </header>
        <div class="card-grid">
            <?php foreach ($careArticles as $article): ?>
                <article class="card">
                    <h2 class="card__title"><?= htmlspecialchars($article['title']) ?></h2>
                    <?php if (!empty($article['summary'])): ?>
                        <div class="rich-text-content">
                            <p><?= nl2br(htmlspecialchars($article['summary'])) ?></p>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($article['reading_time'])): ?>
                        <p class="card__meta">Lesezeit: <?= htmlspecialchars($article['reading_time']) ?> Minuten</p>
                    <?php endif; ?>
                    <div class="card__cta">
                        <a class="button button--outline" href="<?= BASE_URL ?>/index.php?route=care-article&amp;slug=<?= urlencode($article['slug']) ?>">Artikel lesen</a>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php include __DIR__ . '/../partials/footer.php'; ?>
