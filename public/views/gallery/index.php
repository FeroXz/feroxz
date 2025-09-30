<?php include __DIR__ . '/../partials/header.php'; ?>
<h1>Galerie</h1>
<p class="text-muted">Visuelle Eindrücke aus Terrarien, Projekten und Morph-Kombinationen.</p>
<div class="grid cards" style="margin-top:2rem;">
    <?php foreach ($items as $item): ?>
        <article class="card">
            <?php if (!empty($item['image_path'])): ?>
                <img src="<?= BASE_URL . '/' . htmlspecialchars($item['image_path']) ?>" alt="<?= htmlspecialchars($item['title']) ?>">
            <?php endif; ?>
            <h3><?= htmlspecialchars($item['title']) ?></h3>
            <?php if (!empty($item['description'])): ?>
                <p><?= nl2br(htmlspecialchars($item['description'])) ?></p>
            <?php endif; ?>
        </article>
    <?php endforeach; ?>
</div>
<?php include __DIR__ . '/../partials/footer.php'; ?>
