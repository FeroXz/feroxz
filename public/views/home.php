<?php include __DIR__ . '/partials/header.php'; ?>
<section class="section section--hero">
    <div class="section__inner">
        <div class="hero-panel">
            <span class="hero-panel__kicker">FeroxZ Reptile Center</span>
            <h1 class="hero-panel__title">Ein Zuhause für starke Bartagamen-Erfahrungen</h1>
            <div class="hero-panel__body rich-text-content">
                <?= render_rich_text($settings['hero_intro'] ?? 'Verantwortungsvolle Zucht, seriöse Vermittlung und wissenschaftlich geprüfte Pflegeleitfäden für Pogona vitticeps und andere Reptilienarten.') ?>
            </div>
            <ul class="hero-panel__bullets">
                <li>
                    <span aria-hidden="true"></span>
                    <span>Transparente Haltungs- und Übergabekriterien mit Gesundheitschecks und CITES-Hinweisen.</span>
                </li>
                <li>
                    <span aria-hidden="true"></span>
                    <span>Evidenzbasierte Pflegeleitfäden – strukturiert nach Lebensphase, Ernährung und Lichtbedarf.</span>
                </li>
                <li>
                    <span aria-hidden="true"></span>
                    <span>Genetik-Tools für verantwortungsvolle Zuchtplanung und Morph-Dokumentation.</span>
                </li>
            </ul>
            <div class="hero-actions">
                <a href="<?= BASE_URL ?>/index.php?route=care-guide" class="button button--primary">Pflegeleitfaden Bartagame</a>
                <?php if (!empty($featureGeneticsTeaser)): ?>
                    <a href="<?= BASE_URL ?>/index.php?route=genetics" class="button button--outline">Genetik-Rechner</a>
                <?php endif; ?>
                <a href="<?= BASE_URL ?>/index.php?route=adoption" class="button button--outline">Abgabetiere ansehen</a>
            </div>
        </div>
        <div class="hero-metrics">
            <div class="metric-card">
                <span class="metric-card__title">Aktuelle Kennzahlen</span>
                <div class="metric-card__grid">
                    <div class="metric-card__item">
                        <span>Pflegeguides online</span>
                        <span class="metric-card__value"><?= count($careHighlights) ?></span>
                    </div>
                    <div class="metric-card__item">
                        <span>Aktive Tiere im Bestand</span>
                        <span class="metric-card__value"><?= count($animals) ?></span>
                    </div>
                    <div class="metric-card__item">
                        <span>Offene Vermittlungen</span>
                        <span class="metric-card__value"><?= count($listings) ?></span>
                    </div>
                </div>
            </div>
            <div class="metric-card">
                <div class="metric-card__context">
                    Bei Fragen rund um Haltung, Übergabe oder genetische Kombinationen erreichen Sie uns unter
                    <a href="mailto:<?= htmlspecialchars($settings['contact_email'] ?? CONTACT_EMAIL) ?>"><?= htmlspecialchars($settings['contact_email'] ?? CONTACT_EMAIL) ?></a>.
                </div>
            </div>
        </div>
    </div>
</section>

<?php if (!empty($animals)): ?>
<section class="section">
    <div class="section__inner">
        <header class="section-header">
            <h2 class="section-header__title">Unsere Highlights</h2>
            <p class="section-header__description">Ausgewählte Tiere aus dem Bestand – morphologisch dokumentiert und gesundheitsgecheckt.</p>
        </header>
        <div class="card-grid">
            <?php foreach ($animals as $animal): ?>
                <article class="card card--highlight" id="animal-<?= (int)$animal['id'] ?>">
                    <?php if (!empty($animal['image_path'])): ?>
                        <div class="card__media">
                            <?= render_responsive_picture($animal['image_path'], $animal['name'] . ' – ' . $animal['species'], [
                                'sizes' => '(max-width: 768px) 100vw, 420px',
                            ]) ?>
                        </div>
                    <?php endif; ?>
                    <h3 class="card__title">
                        <?= htmlspecialchars($animal['name']) ?>
                        <?php if (!empty($animal['is_piebald'])): ?>
                            <span class="badge" title="Geschecktes Tier" aria-label="Geschecktes Tier">Gescheckt</span>
                        <?php endif; ?>
                    </h3>
                    <p class="card__subtitle"><?= htmlspecialchars($animal['species']) ?></p>
                    <?php if ($badge = render_sex_badge($animal['sex'] ?? null, ['class' => 'badge-gender--inline'])): ?>
                        <?= $badge ?>
                    <?php endif; ?>
                    <?php if (!empty($animal['genetics'])): ?>
                        <div class="card__meta">
                            <span>Genetik: <?= htmlspecialchars($animal['genetics']) ?></span>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($animal['special_notes'])): ?>
                        <div class="rich-text-content">
                            <?= render_rich_text($animal['special_notes']) ?>
                        </div>
                    <?php endif; ?>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<section class="section section--stripe">
    <div class="section__inner">
        <header class="section-header">
            <h2 class="section-header__title">Tiervermittlung</h2>
            <p class="section-header__description">Aktuelle Vermittlungstiere inklusive Standort, Gesundheitsstatus und Übergabemodalitäten.</p>
        </header>
        <div class="rich-text-content">
            <?= render_rich_text($settings['adoption_intro'] ?? '') ?>
        </div>
        <?php if (!empty($listings)): ?>
            <div class="listing-grid">
                <?php foreach ($listings as $listing): ?>
                    <article class="listing-card">
                        <?php if (!empty($listing['image_path'])): ?>
                            <div class="card__media">
                                <?= render_responsive_picture($listing['image_path'], $listing['title'], [
                                    'sizes' => '(max-width: 768px) 100vw, 360px',
                                ]) ?>
                            </div>
                        <?php endif; ?>
                        <h3 class="card__title"><?= htmlspecialchars($listing['title']) ?></h3>
                        <?php if ($badge = render_sex_badge($listing['sex'] ?? null, ['class' => 'badge-gender--inline'])): ?>
                            <?= $badge ?>
                        <?php endif; ?>
                        <div class="listing-card__meta">
                            <?php if (!empty($listing['species'])): ?>
                                <span>Art: <?= htmlspecialchars($listing['species']) ?></span>
                            <?php endif; ?>
                            <?php if (!empty($listing['genetics'])): ?>
                                <span>Genetik: <?= htmlspecialchars($listing['genetics']) ?></span>
                            <?php endif; ?>
                            <?php if (!empty($listing['price'])): ?>
                                <span>Preis: <?= htmlspecialchars($listing['price']) ?></span>
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
        <?php endif; ?>
    </div>
</section>

<?php if (!empty($latestNews)): ?>
<section class="section">
    <div class="section__inner">
        <header class="section-header">
            <h2 class="section-header__title">Neuigkeiten</h2>
            <p class="section-header__description">Aktuelle Meldungen aus Organisation, Bestand und Veranstaltungen.</p>
        </header>
        <div class="card-grid">
            <?php foreach ($latestNews as $post): ?>
                <article class="card card--neutral">
                    <h3 class="card__title"><?= htmlspecialchars($post['title']) ?></h3>
                    <?php if (!empty($post['published_at'])): ?>
                        <p class="card__subtitle">Veröffentlicht am <?= date('d.m.Y', strtotime($post['published_at'])) ?></p>
                    <?php endif; ?>
                    <?php if (!empty($post['excerpt'])): ?>
                        <div class="rich-text-content">
                            <p><?= nl2br(htmlspecialchars($post['excerpt'])) ?></p>
                        </div>
                    <?php endif; ?>
                    <div class="card__cta">
                        <a class="button button--outline" href="<?= BASE_URL ?>/index.php?route=news&amp;slug=<?= urlencode($post['slug']) ?>">Details ansehen</a>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<?php if (!empty($careHighlights)): ?>
<section class="section section--stripe">
    <div class="section__inner">
        <header class="section-header">
            <h2 class="section-header__title">Pflegewissen</h2>
            <p class="section-header__description">Vertiefende Artikel zu Habitat, Ernährung, UV-Bedarf und Rechtlichem.</p>
        </header>
        <div class="card-grid">
            <?php foreach ($careHighlights as $article): ?>
                <article class="card">
                    <h3 class="card__title"><?= htmlspecialchars($article['title']) ?></h3>
                    <?php if (!empty($article['reading_time'])): ?>
                        <p class="card__subtitle">Lesezeit: <?= htmlspecialchars($article['reading_time']) ?> Minuten</p>
                    <?php endif; ?>
                    <?php if (!empty($article['excerpt'])): ?>
                        <div class="rich-text-content">
                            <p><?= htmlspecialchars($article['excerpt']) ?></p>
                        </div>
                    <?php endif; ?>
                    <div class="card__cta">
                        <a class="button button--outline" href="<?= BASE_URL ?>/index.php?route=care-article&amp;slug=<?= urlencode($article['slug']) ?>">Artikel lesen</a>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<section class="section">
    <div class="section__inner">
        <header class="section-header">
            <h2 class="section-header__title">Vertrauen &amp; Transparenz</h2>
            <p class="section-header__description">Wir veröffentlichen alle relevanten Informationen zu Datenschutz, Recht und Ansprechpartnern.</p>
        </header>
        <div class="trust-grid">
            <article class="trust-card">
                <h3>Impressum &amp; Rechtliches</h3>
                <p>Direkter Zugriff auf Impressum, Datenschutz, AGB und CITES-Hinweise für eine transparente Zusammenarbeit.</p>
            </article>
            <article class="trust-card">
                <h3>Kontakt &amp; Beratung</h3>
                <p>Individuelle Beratung zu Haltung, Übergabeoptionen und Genetik. Schreiben Sie uns jederzeit unter <?= htmlspecialchars($settings['contact_email'] ?? CONTACT_EMAIL) ?>.</p>
            </article>
            <article class="trust-card">
                <h3>Partner &amp; Referenzen</h3>
                <p>Wir arbeiten mit Tierärzt:innen, Reptilienparks und verantwortungsvollen Züchter:innen zusammen und veröffentlichen Referenzen transparent.</p>
            </article>
        </div>
    </div>
</section>

<?php include __DIR__ . '/partials/footer.php'; ?>
