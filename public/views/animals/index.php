<?php include __DIR__ . '/../partials/header.php'; ?>
<h1>Tierübersicht</h1>
<p class="text-muted">Alle öffentlich sichtbaren Tiere aus unserem Bestand.</p>
<div class="grid cards" style="margin-top:2rem;">
    <?php foreach ($animals as $animal): ?>
        <article class="card">
            <?php if (!empty($animal['image_path'])): ?>
                <img src="<?= BASE_URL . '/' . htmlspecialchars($animal['image_path']) ?>" alt="<?= htmlspecialchars($animal['name']) ?>">
            <?php endif; ?>
            <h3><?= htmlspecialchars($animal['name']) ?></h3>
            <p><?= htmlspecialchars($animal['species']) ?></p>
            <?php if (!empty($animal['age'])): ?>
                <p><strong>Alter:</strong> <?= htmlspecialchars($animal['age']) ?></p>
            <?php endif; ?>
            <?php if (!empty($animal['genetics'])): ?>
                <span class="badge">Genetik</span>
                <p><?= htmlspecialchars($animal['genetics']) ?></p>
            <?php endif; ?>
            <?php if (!empty($animal['origin'])): ?>
                <p><strong>Herkunft:</strong> <?= htmlspecialchars($animal['origin']) ?></p>
            <?php endif; ?>
            <?php if (!empty($animal['description'])): ?>
                <div class="rich-text-content"><?= render_rich_text($animal['description']) ?></div>
            <?php endif; ?>
        </article>
    <?php endforeach; ?>
</div>
<?php include __DIR__ . '/../partials/footer.php'; ?>
