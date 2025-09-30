<?php include __DIR__ . '/../partials/header.php'; ?>
<article class="content-card">
    <h1><?= htmlspecialchars($page['title']) ?></h1>
    <?php if (!empty($page['excerpt'])): ?>
        <p class="text-muted"><?= htmlspecialchars($page['excerpt']) ?></p>
    <?php endif; ?>
    <div class="rich-text">
        <?= $page['content'] ?>
    </div>
</article>
<?php include __DIR__ . '/../partials/footer.php'; ?>
