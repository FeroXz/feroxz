<section class="card">
    <h2>Genetik-Rechner</h2>
    <form method="get" action="<?= url('genetics/calculator') ?>">
        <input type="hidden" name="route" value="genetics/calculator">
        <label for="species">Art auswählen</label>
        <select id="species" name="species_id" onchange="this.form.submit()">
            <?php foreach ($speciesList as $sp): ?>
                <option value="<?= $sp['id'] ?>" <?= $selectedSpecies && $selectedSpecies['id'] == $sp['id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($sp['common_name']) ?> (<?= htmlspecialchars($sp['latin_name']) ?>)
                </option>
            <?php endforeach; ?>
        </select>
    </form>
</section>

<?php if ($selectedSpecies): ?>
<section class="card">
    <h3>Eltern-Genotypen für <?= htmlspecialchars($selectedSpecies['common_name']) ?></h3>
    <form method="post" action="<?= url('genetics/calculator', ['species_id' => $selectedSpecies['id']]) ?>">
        <div class="grid">
            <?php foreach ($genes as $gene): ?>
                <div class="card">
                    <h4><?= htmlspecialchars($gene['name']) ?> <span class="badge inheritance-<?= htmlspecialchars($gene['inheritance']) ?>"><?= htmlspecialchars($gene['inheritance']) ?></span></h4>
                    <label>Elter A</label>
                    <select name="parent_a[<?= $gene['id'] ?>]">
                        <?php foreach (genotypeOptions($gene['inheritance']) as $value => $label): ?>
                            <option value="<?= $value ?>" <?= (($parentA[$gene['id']] ?? '') === $value) ? 'selected' : '' ?>><?= htmlspecialchars($label) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <label>Elter B</label>
                    <select name="parent_b[<?= $gene['id'] ?>]">
                        <?php foreach (genotypeOptions($gene['inheritance']) as $value => $label): ?>
                            <option value="<?= $value ?>" <?= (($parentB[$gene['id']] ?? '') === $value) ? 'selected' : '' ?>><?= htmlspecialchars($label) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            <?php endforeach; ?>
        </div>
        <button class="button" type="submit">Punnett-Quadrat berechnen</button>
    </form>
</section>
<?php endif; ?>

<?php if (!empty($result)): ?>
<section class="card">
    <h3>Ergebnisse</h3>
    <?php foreach ($result as $entry): ?>
        <div class="card">
            <h4><?= htmlspecialchars($entry['name']) ?> <span class="badge inheritance-<?= htmlspecialchars($entry['inheritance']) ?>"><?= htmlspecialchars($entry['inheritance']) ?></span></h4>
            <div class="calculator-results">
                <?php foreach ($entry['variants'] as $variant): ?>
                    <div class="variant">
                        <div>
                            <strong>Genotyp:</strong> <?= htmlspecialchars($variant['genotype']) ?><br>
                            <strong>Phänotyp:</strong> <?= htmlspecialchars($variant['phenotype']) ?>
                        </div>
                        <div>
                            <strong><?= $variant['probability'] ?> %</strong>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endforeach; ?>
</section>
<?php endif; ?>
