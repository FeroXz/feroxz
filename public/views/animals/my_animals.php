<?php include __DIR__ . '/../partials/header.php'; ?>
<h1>Meine Tiere</h1>
<p class="text-muted">Nur du kannst diese Tiere sehen. Bearbeitungen erfolgen im Adminbereich.</p>
<?php if (empty($animals)): ?>
    <div class="card">Es wurden bislang keine Tiere zugewiesen.</div>
<?php else: ?>
    <div class="grid cards" style="margin-top:2rem;">
        <?php foreach ($animals as $animal): ?>
            <article class="card">
                <?php if (!empty($animal['image_path'])): ?>
                    <img src="<?= BASE_URL . '/' . htmlspecialchars($animal['image_path']) ?>" alt="<?= htmlspecialchars($animal['name']) ?>">
                <?php endif; ?>
                <h3>
                    <?= htmlspecialchars($animal['name']) ?>
                    <?php if (!empty($animal['is_piebald'])): ?>
                        <span class="animal-marker" title="Geschecktes Tier" aria-label="Geschecktes Tier">⬟</span>
                    <?php endif; ?>
                </h3>
                <p><?= htmlspecialchars($animal['species']) ?></p>
                <?php if (!empty($animal['age'])): ?>
                    <p><strong>Alter:</strong> <?= htmlspecialchars($animal['age']) ?></p>
                <?php endif; ?>
                <?php if (!empty($animal['genetics'])): ?>
                    <span class="badge">Genetik</span>
                    <p><?= htmlspecialchars($animal['genetics']) ?></p>
                <?php endif; ?>
                <?php if (!empty($animal['is_piebald'])): ?>
                    <span class="badge badge-pattern">Gescheckt</span>
                <?php endif; ?>
                <?php if (!empty($animal['origin'])): ?>
                    <p><strong>Herkunft:</strong> <?= htmlspecialchars($animal['origin']) ?></p>
                <?php endif; ?>
                <?php if (!empty($animal['special_notes'])): ?>
                    <div class="rich-text-content"><?= render_rich_text($animal['special_notes']) ?></div>
                <?php endif; ?>
            </article>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
<?php include __DIR__ . '/../partials/footer.php'; ?>
