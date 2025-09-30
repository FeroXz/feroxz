<?php include __DIR__ . '/../partials/header.php'; ?>
<article class="card">
    <h1><?= htmlspecialchars($post['title']) ?></h1>
    <?php if (!empty($post['published_at'])): ?>
        <p class="text-muted">Veröffentlicht am <?= date('d.m.Y H:i', strtotime($post['published_at'])) ?></p>
    <?php endif; ?>
    <?php if (!empty($post['excerpt'])): ?>
        <p><em><?= nl2br(htmlspecialchars($post['excerpt'])) ?></em></p>
    <?php endif; ?>
    <div class="rich-text-content"><?= render_rich_text($post['content']) ?></div>
</article>
<?php include __DIR__ . '/../partials/footer.php'; ?>

