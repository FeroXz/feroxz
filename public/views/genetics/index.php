<section class="card">
    <h2>Genetik-Datenbank</h2>
    <p>Entdecke hinterlegte Arten und deren Gene. Die Daten werden dauerhaft in SQLite gespeichert und können im Adminbereich erweitert werden.</p>
    <div class="grid">
        <?php foreach ($species as $entry): ?>
            <article class="card">
                <h3><?= htmlspecialchars($entry['common_name']) ?></h3>
                <p><em><?= htmlspecialchars($entry['latin_name']) ?></em></p>
                <p><?= nl2br(htmlspecialchars($entry['description'])) ?></p>
                <a class="button secondary" href="<?= url('genetics/species', ['slug' => $entry['slug']]) ?>">Details anzeigen</a>
            </article>
        <?php endforeach; ?>
    </div>
</section>
<section class="card">
    <h3>Genetik-Rechner</h3>
    <p>Berechne Punnett-Quadrate für die unterstützten Arten.</p>
    <a class="button" href="<?= url('genetics/calculator') ?>">Zum Rechner</a>
</section>
