<?php include __DIR__ . '/partials/header.php'; ?>
<section class="hero">
    <div>
        <h1><?= htmlspecialchars($settings['site_title'] ?? APP_NAME) ?></h1>
        <p><?= nl2br(htmlspecialchars($settings['hero_intro'] ?? '')) ?></p>
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
                <h3><?= htmlspecialchars($animal['name']) ?></h3>
                <p><?= htmlspecialchars($animal['species']) ?></p>
                <?php if (!empty($animal['genetics'])): ?>
                    <span class="badge">Genetik</span>
                    <p><?= htmlspecialchars($animal['genetics']) ?></p>
                <?php endif; ?>
                <?php if (!empty($animal['special_notes'])): ?>
                    <p><?= nl2br(htmlspecialchars($animal['special_notes'])) ?></p>
                <?php endif; ?>
            </article>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>

<section style="margin-top:3rem;">
    <h2>Tiervermittlung</h2>
    <p><?= nl2br(htmlspecialchars($settings['adoption_intro'] ?? '')) ?></p>
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
                    <p><?= nl2br(htmlspecialchars($listing['description'])) ?></p>
                <?php endif; ?>
                <?php if (!empty($settings['contact_email'])): ?>
                    <a class="btn" href="mailto:<?= htmlspecialchars($settings['contact_email']) ?>?subject=Anfrage%20<?= urlencode($listing['title']) ?>">Direkt anfragen</a>
                <?php endif; ?>
            </article>
        <?php endforeach; ?>
    </div>
</section>
<?php include __DIR__ . '/partials/footer.php'; ?>
