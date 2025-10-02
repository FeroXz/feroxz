<?php include __DIR__ . '/../partials/header.php'; ?>
<section class="section">
    <div class="section__inner">
        <header class="section-header">
            <h1 class="section-header__title">Neuigkeiten</h1>
            <p class="section-header__description">Aktuelle Meldungen, Events und Hintergrundberichte aus dem FeroxZ Reptile Center.</p>
        </header>
        <div class="card-grid">
            <?php foreach ($posts as $post): ?>
                <article class="card card--neutral">
                    <h2 class="card__title"><?= htmlspecialchars($post['title']) ?></h2>
                    <?php if (!empty($post['published_at'])): ?>
                        <p class="card__subtitle">Veröffentlicht am <?= date('d.m.Y', strtotime($post['published_at'])) ?></p>
                    <?php endif; ?>
                    <?php if (!empty($post['excerpt'])): ?>
                        <div class="rich-text-content">
                            <p><?= nl2br(htmlspecialchars($post['excerpt'])) ?></p>
                        </div>
                    <?php endif; ?>
                    <div class="card__cta">
                        <a class="button button--outline" href="<?= BASE_URL ?>/index.php?route=news&amp;slug=<?= urlencode($post['slug']) ?>">Weiterlesen</a>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php include __DIR__ . '/../partials/footer.php'; ?>
