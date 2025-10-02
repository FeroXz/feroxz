<?php include __DIR__ . '/../partials/header.php'; ?>
<section class="section">
    <div class="section__inner">
        <header class="section-header">
            <h1 class="section-header__title">Meine Tiere</h1>
            <p class="section-header__description">Nur für dich sichtbar – verwalte Details im Adminbereich und halte Stammdaten aktuell.</p>
        </header>
        <?php if (empty($animals)): ?>
            <div class="highlight-card">
                <strong>Keine Tiere hinterlegt</strong>
                <p>Aktuell sind dir keine Tiere zugeordnet. Ergänze Tiere im Adminbereich, um ihren Status zu verfolgen.</p>
            </div>
        <?php else: ?>
            <div class="card-grid">
                <?php foreach ($animals as $animal): ?>
                    <article class="card card--neutral" id="animal-<?= (int)$animal['id'] ?>">
                        <?php if (!empty($animal['image_path'])): ?>
                            <div class="card__media">
                                <?= render_responsive_picture($animal['image_path'], $animal['name'], [
                                    'sizes' => '(max-width: 768px) 100vw, 360px',
                                ]) ?>
                            </div>
                        <?php endif; ?>
                        <h2 class="card__title">
                            <?= htmlspecialchars($animal['name']) ?>
                            <?php if (!empty($animal['is_piebald'])): ?>
                                <span class="badge" title="Geschecktes Tier" aria-label="Geschecktes Tier">Gescheckt</span>
                            <?php endif; ?>
                        </h2>
                        <p class="card__subtitle"><?= htmlspecialchars($animal['species']) ?></p>
                        <?php if ($badge = render_sex_badge($animal['sex'] ?? null, ['class' => 'badge-gender--inline'])): ?>
                            <?= $badge ?>
                        <?php endif; ?>
                        <div class="card__meta">
                            <?php if (!empty($animal['age'])): ?>
                                <span>Alter: <?= htmlspecialchars($animal['age']) ?></span>
                            <?php endif; ?>
                            <?php if (!empty($animal['genetics'])): ?>
                                <span>Genetik: <?= htmlspecialchars($animal['genetics']) ?></span>
                            <?php endif; ?>
                            <?php if (!empty($animal['origin'])): ?>
                                <span>Herkunft: <?= htmlspecialchars($animal['origin']) ?></span>
                            <?php endif; ?>
                        </div>
                        <?php if (!empty($animal['special_notes'])): ?>
                            <div class="rich-text-content">
                                <?= render_rich_text($animal['special_notes']) ?>
                            </div>
                        <?php endif; ?>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>
<?php include __DIR__ . '/../partials/footer.php'; ?>
