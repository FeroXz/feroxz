<?php include __DIR__ . '/../partials/header.php'; ?>
<article class="card">
    <h1><?= htmlspecialchars($page['title']) ?></h1>
    <div class="rich-text-content"><?= render_rich_text($page['content']) ?></div>
</article>
<?php include __DIR__ . '/../partials/footer.php'; ?>

