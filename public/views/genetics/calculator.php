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
    <h3>Eltern-Genetik für <?= htmlspecialchars($selectedSpecies['common_name']) ?></h3>
    <p class="help-text">Wähle optional eines deiner gespeicherten Tiere je Elternteil aus oder stelle die Gene manuell zusammen. Lass Gene im Auswahlfeld frei, wenn sie beim Tier nicht auftreten.</p>
    <form method="post" action="<?= url('genetics/calculator', ['species_id' => $selectedSpecies['id']]) ?>">
        <div class="grid parents">
            <div class="card">
                <h4>Elter A</h4>
                <label for="parent-a-animal">Tier auswählen</label>
                <select id="parent-a-animal" name="parent_a_animal">
                    <option value="">Manuelle Auswahl</option>
                    <?php foreach ($animals as $animal): ?>
                        <option value="<?= $animal['id'] ?>" <?= ($parentAnimalSelection['a'] ?? null) === $animal['id'] ? 'selected' : '' ?>><?= htmlspecialchars($animal['name']) ?></option>
                    <?php endforeach; ?>
                </select>
                <label for="parent-a-genes">Gene von Elter A</label>
                <select id="parent-a-genes" name="parent_a_genes[]" multiple size="<?= max(6, min(12, count($genes) * 2)) ?>">
                    <?php foreach ($genes as $gene): ?>
                        <optgroup label="<?= htmlspecialchars($gene['name']) ?> (<?= htmlspecialchars($gene['inheritance']) ?>)">
                            <?php foreach (geneSelectionOptions($gene) as $value => $label): ?>
                                <?php $optionValue = $gene['id'] . ':' . $value; ?>
                                <option value="<?= $optionValue ?>" <?= in_array($optionValue, $parentAValues, true) ? 'selected' : '' ?>><?= htmlspecialchars($label) ?></option>
                            <?php endforeach; ?>
                        </optgroup>
                    <?php endforeach; ?>
                </select>
                <p class="help-text">Aktuelle Auswahl: <?= htmlspecialchars(summarizeGeneStates($genes, $parentA)) ?></p>
            </div>
            <div class="card">
                <h4>Elter B</h4>
                <label for="parent-b-animal">Tier auswählen</label>
                <select id="parent-b-animal" name="parent_b_animal">
                    <option value="">Manuelle Auswahl</option>
                    <?php foreach ($animals as $animal): ?>
                        <option value="<?= $animal['id'] ?>" <?= ($parentAnimalSelection['b'] ?? null) === $animal['id'] ? 'selected' : '' ?>><?= htmlspecialchars($animal['name']) ?></option>
                    <?php endforeach; ?>
                </select>
                <label for="parent-b-genes">Gene von Elter B</label>
                <select id="parent-b-genes" name="parent_b_genes[]" multiple size="<?= max(6, min(12, count($genes) * 2)) ?>">
                    <?php foreach ($genes as $gene): ?>
                        <optgroup label="<?= htmlspecialchars($gene['name']) ?> (<?= htmlspecialchars($gene['inheritance']) ?>)">
                            <?php foreach (geneSelectionOptions($gene) as $value => $label): ?>
                                <?php $optionValue = $gene['id'] . ':' . $value; ?>
                                <option value="<?= $optionValue ?>" <?= in_array($optionValue, $parentBValues, true) ? 'selected' : '' ?>><?= htmlspecialchars($label) ?></option>
                            <?php endforeach; ?>
                        </optgroup>
                    <?php endforeach; ?>
                </select>
                <p class="help-text">Aktuelle Auswahl: <?= htmlspecialchars(summarizeGeneStates($genes, $parentB)) ?></p>
            </div>
        </div>
        <button class="button" type="submit">Punnett-Quadrat berechnen</button>
    </form>
</section>

<?php if (!empty($animals)): ?>
<section class="card">
    <h3>Gespeicherte Tiere</h3>
    <div class="calculator-summary">
        <?php foreach ($animals as $animal): ?>
            <div class="variant">
                <div>
                    <strong><?= htmlspecialchars($animal['name']) ?></strong><br>
                    <small><?= htmlspecialchars($animal['age'] ? $animal['age'] : 'Alter unbekannt') ?></small>
                </div>
                <div class="probability">
                    <?= htmlspecialchars(summarizeGeneStates($genes, buildAnimalGenotypeMap($genes, $animal['genotypes'] ?? []))) ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>
<?php endif; ?>

<?php if (!empty($calculation['combinations'])): ?>
<section class="card">
    <h3>Kombinierte Nachzuchtquoten</h3>
    <div class="calculator-summary">
        <?php foreach ($calculation['combinations'] as $combo): ?>
            <div class="variant">
                <div>
                    <strong><?= htmlspecialchars($combo['label']) ?></strong>
                </div>
                <div class="probability">
                    <?php $summaryProbability = rtrim(rtrim(number_format($combo['probability'], 2), '0'), '.'); ?>
                    <strong><?= $summaryProbability ?> %</strong>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>

<?php if (!empty($calculation['perGene'])): ?>
<section class="card">
    <h3>Ergebnisse pro Gen</h3>
    <?php foreach ($calculation['perGene'] as $entry): ?>
        <div class="card">
            <h4><?= htmlspecialchars($entry['name']) ?> <span class="badge inheritance-<?= htmlspecialchars($entry['inheritance']) ?>"><?= htmlspecialchars($entry['inheritance']) ?></span></h4>
            <div class="calculator-results">
                <?php foreach ($entry['variants'] as $variant): ?>
                    <div class="variant">
                        <div>
                            <strong>Genotyp:</strong> <?= htmlspecialchars($variant['genotype']) ?><br>
                            <strong>Phänotyp:</strong> <?= htmlspecialchars($variant['phenotype']) ?>
                        </div>
                        <div class="probability">
                            <?php $detailProbability = rtrim(rtrim(number_format($variant['probability'], 2), '0'), '.'); ?>
                            <strong><?= $detailProbability ?> %</strong>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endforeach; ?>
</section>
<?php endif; ?>
