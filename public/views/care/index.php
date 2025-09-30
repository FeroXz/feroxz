<?php include __DIR__ . '/../partials/header.php'; ?>
<h1>Pflegeleitfaden</h1>
<p class="text-muted">Wissensdatenbank mit Pflegeprofilen, Technik- und Ernährungsrichtlinien.</p>
<div class="grid cards" style="margin-top:2rem;">
    <?php foreach ($careArticles as $article): ?>
        <article class="card">
            <h2><?= htmlspecialchars($article['title']) ?></h2>
            <?php if (!empty($article['summary'])): ?>
                <p><?= nl2br(htmlspecialchars($article['summary'])) ?></p>
            <?php endif; ?>
            <a class="btn" href="<?= BASE_URL ?>/index.php?route=care-article&amp;slug=<?= urlencode($article['slug']) ?>">Artikel lesen</a>
        </article>
    <?php endforeach; ?>
</div>
<?php include __DIR__ . '/../partials/footer.php'; ?>

