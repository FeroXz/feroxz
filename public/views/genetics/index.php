<?php include __DIR__ . '/../partials/header.php'; ?>
<h1>Genetikrechner</h1>
<p class="text-muted">Planen Sie Ihre Verpaarungen analog zu MorphMarket: Wählen Sie eine Art, hinterlegen Sie die Genetik beider Elternteile und erhalten Sie fundierte Wahrscheinlichkeiten für visuelle Nachzuchten sowie Trägertiere.</p>

<?php if (empty($speciesList)): ?>
    <div class="card">
        <p>Aktuell sind keine genetischen Datensätze hinterlegt. Bitte melden Sie sich als Administrator an, um Arten und Gene zu pflegen.</p>
    </div>
<?php else: ?>
    <div class="card" style="margin-bottom:2rem;">
        <form method="get" style="display:flex;gap:1rem;align-items:flex-end;flex-wrap:wrap;">
            <input type="hidden" name="route" value="genetics">
            <label>Art auswählen
                <select name="species" onchange="this.form.submit()">
                    <?php foreach ($speciesList as $species): ?>
                        <option value="<?= htmlspecialchars($species['slug']) ?>" <?= ($selectedSpeciesSlug === $species['slug']) ? 'selected' : '' ?>><?= htmlspecialchars($species['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </label>
            <noscript>
                <button type="submit">Wechseln</button>
            </noscript>
        </form>
        <?php if ($selectedSpecies): ?>
            <p style="margin-top:0.5rem;">Aktuelle Art: <strong><?= htmlspecialchars($selectedSpecies['name']) ?></strong><?php if (!empty($selectedSpecies['scientific_name'])): ?> (<em><?= htmlspecialchars($selectedSpecies['scientific_name']) ?></em>)<?php endif; ?></p>
            <?php if (!empty($selectedSpecies['description'])): ?>
                <div class="rich-text-content" style="margin-top:0.75rem;">
                    <?= render_rich_text($selectedSpecies['description']) ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>

    <?php if ($selectedSpecies && !empty($genes)): ?>
        <?php
            $toLower = static function (string $value): string {
                return function_exists('mb_strtolower') ? mb_strtolower($value, 'UTF-8') : strtolower($value);
            };
            $modeLabels = [
                'recessive' => 'rezessiv',
                'dominant' => 'dominant',
                'incomplete_dominant' => 'inkomplett dominant',
            ];
            $geneStatePayload = [];
            foreach ($genes as $gene) {
                $geneId = (int)$gene['id'];
                $stateEntries = [];
                foreach (['normal', 'heterozygous', 'homozygous'] as $stateKey) {
                    $label = gene_state_label($gene, $stateKey);
                    $tokens = [$toLower($label), $toLower($gene['name'])];
                    if (!empty($gene['shorthand'])) {
                        $tokens[] = $toLower($gene['shorthand']);
                    }
                    if ($stateKey === 'normal') {
                        $tokens[] = 'wildtyp';
                        $tokens[] = 'normal ' . $toLower($gene['name']);
                    } elseif ($stateKey === 'heterozygous') {
                        $tokens[] = 'het ' . $toLower($gene['name']);
                        $tokens[] = 'träger ' . $toLower($gene['name']);
                    } else {
                        $tokens[] = 'visual ' . $toLower($gene['name']);
                    }
                    $stateEntries[] = [
                        'key' => $stateKey,
                        'label' => $label,
                        'searchTokens' => array_values(array_unique($tokens)),
                    ];
                }
                $geneStatePayload[] = [
                    'id' => $geneId,
                    'name' => $gene['name'],
                    'shorthand' => $gene['shorthand'],
                    'inheritance' => $modeLabels[$gene['inheritance_mode']] ?? $gene['inheritance_mode'],
                    'description' => $gene['description'],
                    'states' => $stateEntries,
                ];
            }
        ?>
        <form method="post" class="card gene-selector" data-genetic-selector>
            <input type="hidden" name="species_slug" value="<?= htmlspecialchars($selectedSpecies['slug']) ?>">
            <div class="gene-selector__intro">
                <p><strong>Eingabehilfe:</strong> Tippen Sie einen Gen-Namen oder Trägerstatus (z.&nbsp;B. „Albino“, „het Toffee“, „Super Anaconda“). Bestätigen Sie den Vorschlag mit Enter oder einem Klick. Nicht ausgewählte Gene werden automatisch als Wildtyp gewertet.</p>
            </div>
            <div class="alert alert-error" data-form-error hidden role="alert" aria-live="assertive"></div>
            <div class="gene-selector__parents">
                <section class="gene-parent" data-parent="parent1">
                    <h2>Elter 1</h2>
                    <p class="text-muted">Fügen Sie alle sichtbaren Morphe sowie Trägereigenschaften hinzu.</p>
                    <div class="gene-parent__tags" data-tag-container></div>
                    <div class="gene-parent__input">
                        <input type="text" placeholder="Gen oder Bezeichnung eingeben …" data-input>
                        <button type="button" class="btn btn-secondary" data-clear>Zurücksetzen</button>
                    </div>
                    <div class="gene-parent__suggestions" data-suggestions hidden></div>
                    <div data-hidden-inputs></div>
                </section>
                <section class="gene-parent" data-parent="parent2">
                    <h2>Elter 2</h2>
                    <p class="text-muted">Bestimmen Sie visuelle Merkmale oder Heterozygotie wie „het Albino“.</p>
                    <div class="gene-parent__tags" data-tag-container></div>
                    <div class="gene-parent__input">
                        <input type="text" placeholder="Gen oder Bezeichnung eingeben …" data-input>
                        <button type="button" class="btn btn-secondary" data-clear>Zurücksetzen</button>
                    </div>
                    <div class="gene-parent__suggestions" data-suggestions hidden></div>
                    <div data-hidden-inputs></div>
                </section>
            </div>
            <button type="submit" class="btn" style="margin-top:1.5rem;align-self:flex-start;">Kombination berechnen</button>
        </form>
        <section class="gene-reference">
            <h2>Verfügbare Gene</h2>
            <div class="grid cards">
                <?php foreach ($genes as $gene): ?>
                    <article class="card gene-reference__card">
                        <header class="gene-reference__header">
                            <div>
                                <h3><?= htmlspecialchars($gene['name']) ?></h3>
                                <?php if (!empty($gene['shorthand'])): ?>
                                    <span class="badge">Kürzel: <?= htmlspecialchars($gene['shorthand']) ?></span>
                                <?php endif; ?>
                            </div>
                            <span class="badge"><?= htmlspecialchars($modeLabels[$gene['inheritance_mode']] ?? $gene['inheritance_mode']) ?></span>
                        </header>
                        <dl class="gene-reference__states">
                            <div><dt>Wildtyp</dt><dd><?= htmlspecialchars(gene_state_label($gene, 'normal')) ?></dd></div>
                            <div><dt>Träger</dt><dd><?= htmlspecialchars(gene_state_label($gene, 'heterozygous')) ?></dd></div>
                            <div><dt>Visuell</dt><dd><?= htmlspecialchars(gene_state_label($gene, 'homozygous')) ?></dd></div>
                        </dl>
                        <?php if (!empty($gene['description'])): ?>
                            <p class="text-muted" style="line-height:1.5;"><?= htmlspecialchars($gene['description']) ?></p>
                        <?php endif; ?>
                    </article>
                <?php endforeach; ?>
            </div>
        </section>
        <script>
            window.GENETIC_GENE_DATA = <?= json_encode($geneStatePayload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>;
            window.GENETIC_PARENT_SELECTIONS = <?= json_encode($parentSelections, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>;
        </script>
    <?php elseif ($selectedSpecies): ?>
        <div class="card" style="margin-bottom:2rem;">
            <p>Für diese Art wurden bislang keine Gene hinterlegt.</p>
        </div>
    <?php endif; ?>

    <?php if (!empty($results)): ?>
        <section style="margin-bottom:3rem;">
            <h2>Gesamtauswertung</h2>
            <div class="card">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Wahrscheinlichkeit</th>
                            <th>Ausprägung</th>
                            <th>Genotyp</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($results['combined'] as $entry): ?>
                            <tr>
                                <td><?= number_format($entry['probability'] * 100, 1, ',', '.') ?>%</td>
                                <td><?= htmlspecialchars($entry['phenotype']) ?></td>
                                <td>
                                    <?php foreach ($entry['labels'] as $label): ?>
                                        <div><?= htmlspecialchars($label) ?></div>
                                    <?php endforeach; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>

        <section style="margin-bottom:3rem;">
            <h2>Genbezogene Verteilung</h2>
            <div class="grid cards">
                <?php foreach ($results['genes'] as $geneResult): ?>
                    <?php $gene = $geneResult['gene']; ?>
                    <article class="card">
                        <h3><?= htmlspecialchars($gene['name']) ?></h3>
                        <p class="text-muted" style="font-size:0.9rem;">Elter 1: <?= htmlspecialchars(gene_state_label($gene, $geneResult['parent_states']['parent_one'])) ?> · Elter 2: <?= htmlspecialchars(gene_state_label($gene, $geneResult['parent_states']['parent_two'])) ?></p>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Genotyp</th>
                                    <th>Wahrscheinlichkeit</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($geneResult['states'] as $state): ?>
                                    <tr>
                                        <td>
                                            <?= htmlspecialchars($state['label']) ?>
                                            <?php if ($state['is_visual']): ?>
                                                <span class="tag tag-visual">visuell</span>
                                            <?php elseif ($state['is_carrier']): ?>
                                                <span class="tag tag-carrier">Träger</span>
                                            <?php else: ?>
                                                <span class="tag tag-normal">Wildtyp</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= number_format($state['probability'] * 100, 1, ',', '.') ?>%</td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </article>
                <?php endforeach; ?>
            </div>
        </section>
    <?php endif; ?>
<?php endif; ?>

<?php include __DIR__ . '/../partials/footer.php'; ?>
