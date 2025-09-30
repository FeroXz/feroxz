<?php include __DIR__ . '/../partials/header.php'; ?>
<article class="content-card">
    <h1><?= htmlspecialchars($post['title']) ?></h1>
    <?php if (!empty($post['published_at'])): ?>
        <p class="text-muted">Veröffentlicht am <?= date('d.m.Y', strtotime($post['published_at'])) ?></p>
    <?php endif; ?>
    <?php if (!empty($post['excerpt'])): ?>
        <p class="lead"><?= htmlspecialchars($post['excerpt']) ?></p>
    <?php endif; ?>
    <div class="rich-text">
        <?= $post['content'] ?>
    </div>
</article>
<?php include __DIR__ . '/../partials/footer.php'; ?>
