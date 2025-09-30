<?php include __DIR__ . '/partials/header.php'; ?>
<section class="hero">
    <div>
        <h1><?= htmlspecialchars($settings['site_title'] ?? APP_NAME) ?></h1>
        <p><?= nl2br(htmlspecialchars($settings['hero_intro'] ?? '')) ?></p>
        <div style="display:flex;gap:1rem;margin-top:1.5rem;flex-wrap:wrap;">
            <a class="btn" href="<?= route_url('genetics/calculator') ?>">Genetik-Rechner öffnen</a>
            <a class="btn btn-secondary" href="<?= route_url('care-guides') ?>">Pflegewissen entdecken</a>
        </div>
    </div>
    <div>
        <span class="badge">FeroxZ Insights</span>
        <p>
            Moderne Glas-Optik trifft auf tief recherchierte Inhalte: Pflegeleitfäden, Genetik-Datenbank, Galerie und
            Adoptionen sind an einem Ort vereint. Administriere Seiten, Beiträge und Menüstrukturen direkt im Browser.
        </p>
    </div>
</section>

<?php if (!empty($animals)): ?>
<section style="margin-top:3rem;">
    <h2>Showcase Tiere</h2>
    <p class="text-muted">Ausgewählte Tiere aus unserem Bestand – perfekt gepflegt und genetisch dokumentiert.</p>
    <div class="grid cards" style="margin-top:1.5rem;">
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
    <div class="grid cards" style="margin-top:1.5rem;">
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

<?php if (!empty($guides)): ?>
<section style="margin-top:3rem;">
    <h2>Pflegeleitfäden</h2>
    <p class="text-muted">Langjährige Erfahrung trifft auf wissenschaftliche Quellen – kompakt für deinen Alltag im Terrarium.</p>
    <div class="grid cards" style="margin-top:1.5rem;">
        <?php foreach ($guides as $guide): ?>
            <article class="card">
                <h3><?= htmlspecialchars($guide['headline']) ?></h3>
                <p class="text-muted"><?= htmlspecialchars($guide['summary']) ?></p>
                <a class="btn btn-secondary" href="<?= route_url('care-guide', ['slug' => $guide['slug']]) ?>">Leitfaden lesen</a>
            </article>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>

<?php if (!empty($posts)): ?>
<section style="margin-top:3rem;">
    <h2>Neu im Journal</h2>
    <div class="grid cards" style="margin-top:1.5rem;">
        <?php foreach ($posts as $post): ?>
            <article class="card">
                <h3><?= htmlspecialchars($post['title']) ?></h3>
                <?php if (!empty($post['excerpt'])): ?>
                    <p class="text-muted"><?= htmlspecialchars($post['excerpt']) ?></p>
                <?php endif; ?>
                <a class="btn btn-secondary" href="<?= route_url('post', ['slug' => $post['slug']]) ?>">Weiterlesen</a>
            </article>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>

<section style="margin-top:3rem;">
    <div class="card" style="background:linear-gradient(160deg, rgba(20,184,166,0.25), rgba(15,23,42,0.8));">
        <h2>Direkter Draht</h2>
        <p>Du planst einen neuen Zuchtansatz oder benötigst ein Health-Check? Schreib uns eine Mail an
            <a href="mailto:<?= htmlspecialchars($settings['contact_email'] ?? 'info@example.com') ?>">
                <?= htmlspecialchars($settings['contact_email'] ?? 'info@example.com') ?></a> und wir melden uns zeitnah.
        </p>
        <div style="margin-top:1rem;">
            <a class="btn" href="<?= route_url('gallery') ?>">Galerie ansehen</a>
        </div>
    </div>
</section>
<?php include __DIR__ . '/partials/footer.php'; ?>
