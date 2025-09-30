<?php include __DIR__ . '/../partials/header.php'; ?>
<article class="card">
    <h1><?= htmlspecialchars($article['title']) ?></h1>
    <?php if (!empty($article['summary'])): ?>
        <p class="text-muted"><?= nl2br(htmlspecialchars($article['summary'])) ?></p>
    <?php endif; ?>
    <div class="rich-text-content"><?= render_rich_text($article['content']) ?></div>
</article>
<?php include __DIR__ . '/../partials/footer.php'; ?>

