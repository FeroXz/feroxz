<?php include __DIR__ . '/partials/header.php'; ?>
<section class="hero">
    <div>
        <h1><?= htmlspecialchars($settings['site_title'] ?? APP_NAME) ?></h1>
        <div class="rich-text-content"><?= render_rich_text($settings['hero_intro'] ?? '') ?></div>
    </div>
    <div>
        <span class="badge">Pflegeleitfaden</span>
        <p>
            Unsere Leitfäden decken Beleuchtung, Ernährung, Habitatgestaltung und Gesundheitsvorsorge für
            <strong>Pogona vitticeps</strong> und <strong>Heterodon nasicus</strong> ab. Registrierte Benutzer erhalten
            Zugriff auf individuelle Tierakten inklusive Genetik und Besonderheiten.
        </p>
    </div>
</section>

<?php if (!empty($animals)): ?>
<section style="margin-top:3rem;">
    <h2>Unsere Highlights</h2>
    <div class="grid cards">
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
            <?php if (!empty($animal['genetics'])): ?>
                <span class="badge">Genetik</span>
                <p><?= htmlspecialchars($animal['genetics']) ?></p>
            <?php endif; ?>
            <?php if (!empty($animal['is_piebald'])): ?>
                <span class="badge badge-pattern">Gescheckt</span>
            <?php endif; ?>
                <?php if (!empty($animal['special_notes'])): ?>
                    <div class="rich-text-content"><?= render_rich_text($animal['special_notes']) ?></div>
                <?php endif; ?>
            </article>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>

<section style="margin-top:3rem;">
    <h2>Tiervermittlung</h2>
    <div class="rich-text-content"><?= render_rich_text($settings['adoption_intro'] ?? '') ?></div>
    <div class="grid cards">
        <?php foreach ($listings as $listing): ?>
            <article class="card">
                <?php if (!empty($listing['image_path'])): ?>
                    <img src="<?= BASE_URL . '/' . htmlspecialchars($listing['image_path']) ?>" alt="<?= htmlspecialchars($listing['title']) ?>">
                <?php endif; ?>
                <h3><?= htmlspecialchars($listing['title']) ?></h3>
                <?php if (!empty($listing['species'])): ?>
                    <p><?= htmlspecialchars($listing['species']) ?></p>
                <?php endif; ?>
                <?php if (!empty($listing['genetics'])): ?>
                    <span class="badge">Genetik</span>
                    <p><?= htmlspecialchars($listing['genetics']) ?></p>
                <?php endif; ?>
                <?php if (!empty($listing['price'])): ?>
                    <p><strong>Preis:</strong> <?= htmlspecialchars($listing['price']) ?></p>
                <?php endif; ?>
                <?php if (!empty($listing['description'])): ?>
                    <div class="rich-text-content"><?= render_rich_text($listing['description']) ?></div>
                <?php endif; ?>
                <?php if (!empty($settings['contact_email'])): ?>
                    <a class="btn" href="mailto:<?= htmlspecialchars($settings['contact_email']) ?>?subject=Anfrage%20<?= urlencode($listing['title']) ?>">Direkt anfragen</a>
                <?php endif; ?>
            </article>
        <?php endforeach; ?>
    </div>
</section>
<?php if (!empty($latestNews)): ?>
<section style="margin-top:3rem;">
    <h2>Neuigkeiten</h2>
    <div class="grid cards">
        <?php foreach ($latestNews as $post): ?>
            <article class="card">
                <h3><?= htmlspecialchars($post['title']) ?></h3>
                <?php if (!empty($post['published_at'])): ?>
                    <p class="text-muted"><?= date('d.m.Y', strtotime($post['published_at'])) ?></p>
                <?php endif; ?>
                <?php if (!empty($post['excerpt'])): ?>
                    <p><?= nl2br(htmlspecialchars($post['excerpt'])) ?></p>
                <?php endif; ?>
                <a class="btn btn-secondary" href="<?= BASE_URL ?>/index.php?route=news&amp;slug=<?= urlencode($post['slug']) ?>">Details</a>
            </article>
        <?php endforeach; ?>
    </div>
    <div style="margin-top:1rem;">
        <a class="btn" href="<?= BASE_URL ?>/index.php?route=news">Alle Meldungen anzeigen</a>
    </div>
</section>
<?php endif; ?>

<?php if (!empty($careHighlights)): ?>
<section style="margin-top:3rem;">
    <h2>Pflegewissen</h2>
    <div class="grid cards">
        <?php foreach ($careHighlights as $article): ?>
            <article class="card">
                <h3><?= htmlspecialchars($article['title']) ?></h3>
                <?php if (!empty($article['summary'])): ?>
                    <p><?= nl2br(htmlspecialchars($article['summary'])) ?></p>
                <?php endif; ?>
                <a class="btn btn-secondary" href="<?= BASE_URL ?>/index.php?route=care-article&amp;slug=<?= urlencode($article['slug']) ?>">Leitfaden öffnen</a>
            </article>
        <?php endforeach; ?>
    </div>
    <div style="margin-top:1rem;">
        <a class="btn" href="<?= BASE_URL ?>/index.php?route=care-guide">Zur Wissenssammlung</a>
    </div>
</section>
<?php endif; ?>

<?php include __DIR__ . '/partials/footer.php'; ?>
