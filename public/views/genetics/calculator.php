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
                <div class="gene-selector" data-input-name="parent_a_genes[]">
                    <label for="parent-a-gene-search">Gene hinzufügen</label>
                    <div class="gene-search">
                        <input id="parent-a-gene-search" class="gene-search-input" type="text" list="parent-a-gene-options" placeholder="Gen suchen...">
                        <datalist id="parent-a-gene-options">
                            <?php foreach ($genes as $gene): ?>
                                <option value="<?= htmlspecialchars($gene['name']) ?>"></option>
                            <?php endforeach; ?>
                        </datalist>
                        <button class="button subtle small gene-add-button" type="button">Hinzufügen</button>
                    </div>
                    <div class="selected-genes">
                        <p class="no-genes">Noch keine Gene ausgewählt.</p>
                        <?php
                        $genesById = [];
                        foreach ($genes as $gene) {
                            $genesById[$gene['id']] = $gene;
                        }
                        foreach ($parentAValues as $value):
                            [$geneId, $state] = explode(':', $value, 2);
                            $gene = $genesById[$geneId] ?? null;
                            if (!$gene) {
                                continue;
                            }
                            $defaultState = defaultGenotypeForInheritance($gene['inheritance']);
                            $options = geneSelectionOptions($gene);
                        ?>
                            <div class="selected-gene" data-gene-id="<?= (int)$geneId ?>">
                                <div class="selected-gene-name"><?= htmlspecialchars($gene['name']) ?></div>
                                <select class="gene-state" data-default-state="<?= htmlspecialchars($defaultState) ?>">
                                    <option value="">Nicht ausgewählt (Normal)</option>
                                    <?php foreach ($options as $optionValue => $label): ?>
                                        <option value="<?= htmlspecialchars($optionValue) ?>" <?= $optionValue === $state ? 'selected' : '' ?>><?= htmlspecialchars($label) ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <button type="button" class="button subtle small gene-remove">Entfernen</button>
                                <input type="hidden" name="parent_a_genes[]" value="<?= (int)$geneId ?>:<?= htmlspecialchars($state) ?>" class="gene-value">
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
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
                <div class="gene-selector" data-input-name="parent_b_genes[]">
                    <label for="parent-b-gene-search">Gene hinzufügen</label>
                    <div class="gene-search">
                        <input id="parent-b-gene-search" class="gene-search-input" type="text" list="parent-b-gene-options" placeholder="Gen suchen...">
                        <datalist id="parent-b-gene-options">
                            <?php foreach ($genes as $gene): ?>
                                <option value="<?= htmlspecialchars($gene['name']) ?>"></option>
                            <?php endforeach; ?>
                        </datalist>
                        <button class="button subtle small gene-add-button" type="button">Hinzufügen</button>
                    </div>
                    <div class="selected-genes">
                        <p class="no-genes">Noch keine Gene ausgewählt.</p>
                        <?php
                        foreach ($parentBValues as $value):
                            [$geneId, $state] = explode(':', $value, 2);
                            $gene = $genesById[$geneId] ?? null;
                            if (!$gene) {
                                continue;
                            }
                            $defaultState = defaultGenotypeForInheritance($gene['inheritance']);
                            $options = geneSelectionOptions($gene);
                        ?>
                            <div class="selected-gene" data-gene-id="<?= (int)$geneId ?>">
                                <div class="selected-gene-name"><?= htmlspecialchars($gene['name']) ?></div>
                                <select class="gene-state" data-default-state="<?= htmlspecialchars($defaultState) ?>">
                                    <option value="">Nicht ausgewählt (Normal)</option>
                                    <?php foreach ($options as $optionValue => $label): ?>
                                        <option value="<?= htmlspecialchars($optionValue) ?>" <?= $optionValue === $state ? 'selected' : '' ?>><?= htmlspecialchars($label) ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <button type="button" class="button subtle small gene-remove">Entfernen</button>
                                <input type="hidden" name="parent_b_genes[]" value="<?= (int)$geneId ?>:<?= htmlspecialchars($state) ?>" class="gene-value">
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
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
    <?php
    $geneCatalogData = array_map(function ($gene) {
        return [
            'id' => (int)$gene['id'],
            'name' => $gene['name'],
            'inheritance' => $gene['inheritance'],
            'default' => defaultGenotypeForInheritance($gene['inheritance']),
            'options' => geneSelectionOptions($gene)
        ];
    }, $genes);
    ?>
    <script>
        (() => {
            const catalog = <?= json_encode($geneCatalogData, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>;
            const catalogByName = new Map(catalog.map((gene) => [gene.name.toLowerCase(), gene]));
            const catalogById = new Map(catalog.map((gene) => [String(gene.id), gene]));

            const updatePlaceholder = (container) => {
                if (!container) {
                    return;
                }
                const placeholder = container.querySelector('.no-genes');
                if (!placeholder) {
                    return;
                }
                const hasSelections = container.querySelectorAll('.selected-gene').length > 0;
                placeholder.style.display = hasSelections ? 'none' : '';
            };

            const bindRow = (wrapper, row, gene) => {
                if (!wrapper || !row || !gene || row.dataset.bound === '1') {
                    return;
                }
                row.dataset.bound = '1';
                const select = row.querySelector('.gene-state');
                const removeBtn = row.querySelector('.gene-remove');
                let hidden = row.querySelector('.gene-value');
                if (!select) {
                    return;
                }
                if (!hidden) {
                    hidden = document.createElement('input');
                    hidden.type = 'hidden';
                    hidden.className = 'gene-value';
                    hidden.name = wrapper.dataset.inputName;
                    row.appendChild(hidden);
                } else {
                    hidden.name = wrapper.dataset.inputName;
                }
                select.dataset.defaultState = select.dataset.defaultState || gene.default;

                const sync = () => {
                    const value = select.value;
                    if (!value || value === '' || value === select.dataset.defaultState) {
                        hidden.disabled = true;
                        hidden.value = '';
                    } else {
                        hidden.disabled = false;
                        hidden.value = gene.id + ':' + value;
                    }
                };

                select.addEventListener('change', () => {
                    sync();
                });
                if (removeBtn) {
                    removeBtn.addEventListener('click', () => {
                        row.remove();
                        updatePlaceholder(wrapper.querySelector('.selected-genes'));
                    });
                }
                sync();
            };

            const createRow = (wrapper, gene) => {
                const container = wrapper.querySelector('.selected-genes');
                if (!container) {
                    return;
                }
                const existing = container.querySelector(`.selected-gene[data-gene-id="${gene.id}"]`);
                if (existing) {
                    const select = existing.querySelector('.gene-state');
                    if (select) {
                        select.focus();
                    }
                    return;
                }

                const row = document.createElement('div');
                row.className = 'selected-gene';
                row.dataset.geneId = gene.id;

                const name = document.createElement('div');
                name.className = 'selected-gene-name';
                name.textContent = gene.name;
                row.appendChild(name);

                const select = document.createElement('select');
                select.className = 'gene-state';
                select.dataset.defaultState = gene.default;

                const blankOption = document.createElement('option');
                blankOption.value = '';
                blankOption.textContent = 'Nicht ausgewählt (Normal)';
                blankOption.selected = true;
                select.appendChild(blankOption);

                Object.entries(gene.options).forEach(([value, label]) => {
                    const option = document.createElement('option');
                    option.value = value;
                    option.textContent = label;
                    select.appendChild(option);
                });
                row.appendChild(select);

                const removeBtn = document.createElement('button');
                removeBtn.type = 'button';
                removeBtn.className = 'button subtle small gene-remove';
                removeBtn.textContent = 'Entfernen';
                row.appendChild(removeBtn);

                container.appendChild(row);
                bindRow(wrapper, row, gene);
                updatePlaceholder(container);
                select.focus();
            };

            document.querySelectorAll('.gene-selector').forEach((wrapper) => {
                const container = wrapper.querySelector('.selected-genes');
                const input = wrapper.querySelector('.gene-search-input');
                const button = wrapper.querySelector('.gene-add-button');

                updatePlaceholder(container);

                wrapper.querySelectorAll('.selected-gene').forEach((row) => {
                    const geneId = row.getAttribute('data-gene-id');
                    const gene = catalogById.get(String(geneId));
                    bindRow(wrapper, row, gene);
                });

                const tryAddGene = () => {
                    if (!input) {
                        return;
                    }
                    const query = input.value.trim().toLowerCase();
                    if (!query) {
                        return;
                    }
                    const gene = catalogByName.get(query);
                    if (!gene) {
                        input.classList.add('input-error');
                        return;
                    }
                    input.classList.remove('input-error');
                    createRow(wrapper, gene);
                    input.value = '';
                };

                if (button) {
                    button.addEventListener('click', tryAddGene);
                }

                if (input) {
                    input.addEventListener('input', () => {
                        if (input.classList.contains('input-error')) {
                            input.classList.remove('input-error');
                        }
                    });
                    input.addEventListener('change', tryAddGene);
                    input.addEventListener('keydown', (event) => {
                        if (event.key === 'Enter') {
                            event.preventDefault();
                            tryAddGene();
                        }
                    });
                }
            });
        })();
    </script>
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
