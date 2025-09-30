<?php include __DIR__ . '/../partials/header.php'; ?>
<h1>Neuigkeiten</h1>
<p class="text-muted">Aktuelle Updates aus dem FeroxZ Center.</p>
<div class="grid cards" style="margin-top:2rem;">
    <?php foreach ($newsPosts as $post): ?>
        <article class="card">
            <h2><?= htmlspecialchars($post['title']) ?></h2>
            <?php if (!empty($post['published_at'])): ?>
                <p class="text-muted">Veröffentlicht am <?= date('d.m.Y H:i', strtotime($post['published_at'])) ?></p>
            <?php endif; ?>
            <?php if (!empty($post['excerpt'])): ?>
                <p><?= nl2br(htmlspecialchars($post['excerpt'])) ?></p>
            <?php endif; ?>
            <a class="btn" href="<?= BASE_URL ?>/index.php?route=news&amp;slug=<?= urlencode($post['slug']) ?>">Weiterlesen</a>
        </article>
    <?php endforeach; ?>
</div>
<?php include __DIR__ . '/../partials/footer.php'; ?>

