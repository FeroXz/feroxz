<?php include __DIR__ . '/../partials/header.php'; ?>
<h1>Genetik Datenbank</h1>
<p class="text-muted">Durchsuche alle bekannten Varianten für unsere Kernarten und plane Zuchtpaare mit dem MorphMarket-inspirierten Rechner.</p>
<div class="grid cards" style="margin-top:2rem;">
    <?php foreach ($species as $entry): ?>
        <article class="card">
            <h3><?= htmlspecialchars($entry['name']) ?></h3>
            <p class="text-muted"><em><?= htmlspecialchars($entry['scientific_name']) ?></em></p>
            <p><?= nl2br(htmlspecialchars($entry['description'])) ?></p>
            <div style="display:flex;gap:0.75rem;flex-wrap:wrap;margin-top:1rem;">
                <a class="btn btn-secondary" href="<?= route_url('genetics/species', ['slug' => $entry['slug']]) ?>">Gene anzeigen</a>
                <a class="btn" href="<?= route_url('genetics/calculator', ['species' => $entry['slug']]) ?>">Rechner öffnen</a>
            </div>
        </article>
    <?php endforeach; ?>
</div>
<?php include __DIR__ . '/../partials/footer.php'; ?>
