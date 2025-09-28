<article class="card">
    <h2><?= htmlspecialchars($species['common_name']) ?> <small>(<?= htmlspecialchars($species['latin_name']) ?>)</small></h2>
    <p><?= nl2br(htmlspecialchars($species['description'])) ?></p>
    <?php if (!empty($species['habitat'])): ?>
        <h3>Lebensraum</h3>
        <p><?= nl2br(htmlspecialchars($species['habitat'])) ?></p>
    <?php endif; ?>
    <?php if (!empty($species['care_notes'])): ?>
        <h3>Haltungs-Hinweise</h3>
        <p><?= nl2br(htmlspecialchars($species['care_notes'])) ?></p>
    <?php endif; ?>
</article>

<section class="card">
    <h3>Gene</h3>
    <table class="table">
        <thead>
            <tr>
                <th>Gen</th>
                <th>Vererbung</th>
                <th>Beschreibung</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($genes as $gene): ?>
                <tr>
                    <td><?= htmlspecialchars($gene['name']) ?></td>
                    <td><span class="badge inheritance-<?= htmlspecialchars($gene['inheritance']) ?>"><?= htmlspecialchars($gene['inheritance']) ?></span></td>
                    <td><?= nl2br(htmlspecialchars($gene['description'])) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <a class="button" href="<?= url('genetics/calculator', ['species_id' => $species['id']]) ?>">Rechner starten</a>
</section>
