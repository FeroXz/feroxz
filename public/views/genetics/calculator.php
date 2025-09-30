<?php include __DIR__ . '/../partials/header.php'; ?>
<h1>Genetik Rechner</h1>
<p class="text-muted">Wähle eine Spezies, kombiniere die vorhandenen Morphs und erhalte Wahrscheinlichkeiten analog zum MorphMarket-Rechner.</p>
<form method="post" class="genetics-form" style="margin-top:2rem;">
    <div class="form-row">
        <label for="species">Spezies</label>
        <select name="species" id="species" onchange="this.form.submit()">
            <?php foreach ($speciesList as $entry): ?>
                <option value="<?= htmlspecialchars($entry['slug']) ?>" <?= ($species && $species['slug'] === $entry['slug']) ? 'selected' : '' ?>><?= htmlspecialchars($entry['name']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="parent-grid">
        <section>
            <h2>Elter A</h2>
            <label for="parent_a_animal">Gespeichertes Tier</label>
            <select name="parent_a_animal" id="parent_a_animal" onchange="this.form.submit()">
                <option value="0">– Auswahl –</option>
                <?php foreach ($availableAnimals as $animal): ?>
                    <option value="<?= $animal['id'] ?>" <?= $selectedAnimalA === (int)$animal['id'] ? 'selected' : '' ?>><?= htmlspecialchars($animal['name']) ?></option>
                <?php endforeach; ?>
            </select>
            <label for="parent_a">Gene</label>
            <select name="parent_a[]" id="parent_a" multiple size="10">
                <?php foreach ($geneOptions as $slug => $group): ?>
                    <optgroup label="<?= htmlspecialchars($group['name']) ?>">
                        <?php foreach ($group['options'] as $option): ?>
                            <option value="<?= htmlspecialchars($option['value']) ?>" <?= in_array($option['value'], (array)$parentASelection, true) ? 'selected' : '' ?>><?= htmlspecialchars($option['label']) ?></option>
                        <?php endforeach; ?>
                    </optgroup>
                <?php endforeach; ?>
            </select>
        </section>
        <section>
            <h2>Elter B</h2>
            <label for="parent_b_animal">Gespeichertes Tier</label>
            <select name="parent_b_animal" id="parent_b_animal" onchange="this.form.submit()">
                <option value="0">– Auswahl –</option>
                <?php foreach ($availableAnimals as $animal): ?>
                    <option value="<?= $animal['id'] ?>" <?= $selectedAnimalB === (int)$animal['id'] ? 'selected' : '' ?>><?= htmlspecialchars($animal['name']) ?></option>
                <?php endforeach; ?>
            </select>
            <label for="parent_b">Gene</label>
            <select name="parent_b[]" id="parent_b" multiple size="10">
                <?php foreach ($geneOptions as $slug => $group): ?>
                    <optgroup label="<?= htmlspecialchars($group['name']) ?>">
                        <?php foreach ($group['options'] as $option): ?>
                            <option value="<?= htmlspecialchars($option['value']) ?>" <?= in_array($option['value'], (array)$parentBSelection, true) ? 'selected' : '' ?>><?= htmlspecialchars($option['label']) ?></option>
                        <?php endforeach; ?>
                    </optgroup>
                <?php endforeach; ?>
            </select>
        </section>
    </div>
    <div style="margin-top:1.5rem;display:flex;gap:1rem;flex-wrap:wrap;">
        <button type="submit" class="btn">Wahrscheinlichkeiten berechnen</button>
        <a class="btn btn-secondary" href="<?= route_url('genetics/calculator', ['species' => $species['slug'] ?? ($speciesList[0]['slug'] ?? '')]) ?>">Zurücksetzen</a>
    </div>
</form>

<?php if ($results): ?>
<section style="margin-top:3rem;">
    <h2>Erwartete Nachzucht</h2>
    <div class="grid cards">
        <?php foreach ($results['summaries'] as $summary): ?>
            <article class="card">
                <h3><?= htmlspecialchars($summary['label']) ?></h3>
                <p class="text-muted"><?= number_format($summary['probability'] * 100, 2) ?> %</p>
            </article>
        <?php endforeach; ?>
    </div>
</section>
<section style="margin-top:3rem;">
    <h2>Detail je Gen</h2>
    <div class="grid gene-results">
       <?php foreach ($results['perGene'] as $entry): ?>
            <?php
                $gene = $entry['gene'];
                $stateALabel = $entry['stateA'];
                $stateBLabel = $entry['stateB'];
                foreach (gene_state_options($gene) as $option) {
                    if ($option['value'] === $gene['slug'] . ':' . $entry['stateA']) {
                        $stateALabel = $option['label'];
                    }
                    if ($option['value'] === $gene['slug'] . ':' . $entry['stateB']) {
                        $stateBLabel = $option['label'];
                    }
                }
            ?>
            <article class="card">
                <h3><?= htmlspecialchars($gene['name']) ?></h3>
                <p class="text-muted">A: <?= htmlspecialchars($stateALabel) ?> • B: <?= htmlspecialchars($stateBLabel) ?></p>
                <ul>
                    <?php foreach ($entry['outcomes'] as $outcome): ?>
                        <li><?= htmlspecialchars($outcome['label']) ?> – <?= number_format($outcome['probability'] * 100, 2) ?> %</li>
                    <?php endforeach; ?>
                </ul>
            </article>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>
<?php include __DIR__ . '/../partials/footer.php'; ?>
