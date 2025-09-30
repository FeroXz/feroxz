<?php include __DIR__ . '/../partials/header.php'; ?>
<h1>Tierabgabe</h1>
<p><?= nl2br(htmlspecialchars($settings['adoption_intro'] ?? '')) ?></p>
<?php if ($flashSuccess): ?>
    <div class="alert alert-success"><?= htmlspecialchars($flashSuccess) ?></div>
<?php endif; ?>
<?php if ($flashError): ?>
    <div class="alert alert-error"><?= htmlspecialchars($flashError) ?></div>
<?php endif; ?>
<div class="grid cards" style="margin-top:2rem;">
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
            <form method="post" class="card" style="background:rgba(148,163,184,0.08);margin-top:1rem;">
                <input type="hidden" name="listing_id" value="<?= (int)$listing['id'] ?>">
                <label>Interessiert an
                    <input type="text" name="interested_in" value="<?= htmlspecialchars($listing['title']) ?>">
                </label>
                <label>Name
                    <input type="text" name="name" required>
                </label>
                <label>E-Mail
                    <input type="email" name="email" required>
                </label>
                <label>Nachricht
                    <textarea name="message" required placeholder="Beschreibe deine Haltung, Erfahrung und Fragen."></textarea>
                </label>
                <button type="submit">Anfrage senden</button>
            </form>
        </article>
    <?php endforeach; ?>
</div>
<?php include __DIR__ . '/../partials/footer.php'; ?>
