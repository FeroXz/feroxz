<?php include __DIR__ . '/../partials/header.php'; ?>
<section class="section">
    <div class="section__inner">
        <header class="section-header">
            <h1 class="section-header__title">Tierabgabe &amp; Vermittlung</h1>
            <p class="section-header__description">Alle aktuellen Vermittlungsinserate mit Morph, Geschlecht, Preis und Kontaktmöglichkeit.</p>
        </header>
        <?php if (!empty($settings['adoption_intro'])): ?>
            <div class="rich-text-content">
                <?= render_rich_text($settings['adoption_intro']) ?>
            </div>
        <?php endif; ?>
        <div class="listing-grid">
            <?php foreach ($listings as $listing): ?>
                <article class="listing-card" id="listing-<?= (int)$listing['id'] ?>">
                    <?php if (!empty($listing['image_path'])): ?>
                        <div class="card__media">
                            <?= render_responsive_picture($listing['image_path'], $listing['title'], [
                                'sizes' => '(max-width: 768px) 100vw, 420px',
                            ]) ?>
                        </div>
                    <?php endif; ?>
                    <h2 class="card__title"><?= htmlspecialchars($listing['title']) ?></h2>
                    <?php if ($badge = render_sex_badge($listing['sex'] ?? null, ['class' => 'badge-gender--inline'])): ?>
                        <?= $badge ?>
                    <?php endif; ?>
                    <div class="listing-card__meta">
                        <?php if (!empty($listing['species'])): ?>
                            <span>Art: <?= htmlspecialchars($listing['species']) ?></span>
                        <?php endif; ?>
                        <?php if (!empty($listing['morph'])): ?>
                            <span>Morph: <?= htmlspecialchars($listing['morph']) ?></span>
                        <?php endif; ?>
                        <?php if (!empty($listing['genetics'])): ?>
                            <span>Genetik: <?= htmlspecialchars($listing['genetics']) ?></span>
                        <?php endif; ?>
                        <?php if (!empty($listing['birth_year'])): ?>
                            <span>Geburtsjahr: <?= htmlspecialchars($listing['birth_year']) ?></span>
                        <?php endif; ?>
                        <?php if (!empty($listing['price'])): ?>
                            <span>Preis: <?= htmlspecialchars($listing['price']) ?></span>
                        <?php endif; ?>
                        <?php if (!empty($listing['location'])): ?>
                            <span>Standort: <?= htmlspecialchars($listing['location']) ?></span>
                        <?php endif; ?>
                    </div>
                    <?php if (!empty($listing['description'])): ?>
                        <div class="rich-text-content">
                            <?= render_rich_text($listing['description']) ?>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($settings['contact_email'])): ?>
                        <div class="listing-card__cta">
                            <a class="button button--outline" href="mailto:<?= htmlspecialchars($settings['contact_email']) ?>?subject=Anfrage%20<?= urlencode($listing['title']) ?>">Direkt anfragen</a>
                        </div>
                    <?php endif; ?>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php include __DIR__ . '/../partials/footer.php'; ?>
