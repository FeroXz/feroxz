<?php include __DIR__ . '/../partials/header.php'; ?>
<section class="mx-auto w-full max-w-7xl px-4 sm:px-6 lg:px-8">
<h1 class="text-3xl font-semibold text-white sm:text-4xl">Genetikrechner</h1>
<p class="mt-2 text-sm text-slate-300">Planen Sie Ihre Verpaarungen analog zu MorphMarket: Wählen Sie eine Art, hinterlegen Sie die Genetik beider Elternteile und erhalten Sie fundierte Wahrscheinlichkeiten für visuelle Nachzuchten sowie Trägertiere.</p>

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
            <header class="gene-selector__header">
                <h2>Elterliche Genetik eingeben</h2>
                <p>Tippen Sie einen Gen-Namen, wählen Sie den passenden Vorschlag und bauen Sie die vollständige Genetik beider Elternteile auf.</p>
            </header>
            <div class="alert alert-error" data-form-error hidden role="alert" aria-live="assertive"></div>
            <div class="gene-selector__parents">
                <section class="gene-parent" data-parent="parent1">
                    <div class="gene-parent__header">
                        <h3>Elter 1</h3>
                        <button type="button" class="gene-parent__clear" data-clear>Leeren</button>
                    </div>
                    <label class="sr-only" for="genetics-parent1-input">Gene Elter 1</label>
                    <div class="gene-multiselect" data-multiselect>
                        <div class="gene-multiselect__body">
                            <div class="gene-multiselect__tags" data-placeholder="Wildtyp" data-tag-container></div>
                            <input id="genetics-parent1-input" type="text" placeholder="Gen hinzufügen …" autocomplete="off" data-input>
                        </div>
                    </div>
                    <div class="gene-parent__suggestions" data-suggestions hidden></div>
                    <p class="gene-parent__hint">Fügen Sie visuelle Morphe oder Trägereigenschaften wie „het Albino“ hinzu.</p>
                    <div data-hidden-inputs></div>
                </section>
                <section class="gene-parent" data-parent="parent2">
                    <div class="gene-parent__header">
                        <h3>Elter 2</h3>
                        <button type="button" class="gene-parent__clear" data-clear>Leeren</button>
                    </div>
                    <label class="sr-only" for="genetics-parent2-input">Gene Elter 2</label>
                    <div class="gene-multiselect" data-multiselect>
                        <div class="gene-multiselect__body">
                            <div class="gene-multiselect__tags" data-placeholder="Wildtyp" data-tag-container></div>
                            <input id="genetics-parent2-input" type="text" placeholder="Gen hinzufügen …" autocomplete="off" data-input>
                        </div>
                    </div>
                    <div class="gene-parent__suggestions" data-suggestions hidden></div>
                    <p class="gene-parent__hint">Jeder Eintrag wird direkt übernommen – nicht hinzugefügte Gene bleiben Wildtyp.</p>
                    <div data-hidden-inputs></div>
                </section>
            </div>
            <div class="gene-selector__actions">
                <button type="submit" class="btn">Berechnen</button>
                <button type="button" class="btn btn-secondary" data-clear-all>Zurücksetzen</button>
            </div>
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

    <?php if ($selectedSpecies): ?>
        <section class="gene-results">
            <div class="card gene-results__card">
                <?php if (!empty($results)): ?>
                    <table class="gene-results__table">
                        <thead>
                            <tr>
                                <th>Prob.</th>
                                <th>Traits</th>
                                <th>Morph</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($results['combined'] as $entry): ?>
                                <?php
                                    $fraction = probability_to_fraction_components($entry['probability']);
                                    $tags = [];
                                    $morphParts = [];
                                    foreach ($entry['states'] as $geneId => $stateKey) {
                                        $geneResult = $results['genes'][$geneId] ?? null;
                                        if (!$geneResult) {
                                            continue;
                                        }
                                        $gene = $geneResult['gene'];
                                        $label = $entry['labels'][$geneId] ?? gene_state_label($gene, $stateKey);
                                        if (gene_state_is_visual($gene, $stateKey)) {
                                            $tags[] = ['label' => $label, 'type' => 'visual'];
                                            $morphParts[] = $label;
                                        } elseif (gene_state_is_carrier($gene, $stateKey)) {
                                            $tags[] = ['label' => $label, 'type' => 'carrier'];
                                            $clean = trim(preg_replace('/^het\s+/i', '', $label));
                                            $morphParts[] = 'Het ' . ($clean !== '' ? $clean : $gene['name']);
                                        }
                                    }
                                    if (empty($morphParts)) {
                                        $morphName = 'Wildtyp';
                                    } else {
                                        $morphName = implode(' ', $morphParts);
                                    }
                                ?>
                                <tr>
                                    <td>
                                        <span class="gene-results__fraction"><?= $fraction['numerator'] ?>/<?= $fraction['denominator'] ?></span>
                                        <span class="gene-results__percentage"><?= number_format($fraction['percentage'], 1, ',', '.') ?>%</span>
                                    </td>
                                    <td>
                                        <?php if (!empty($tags)): ?>
                                            <div class="gene-results__tags">
                                                <?php foreach ($tags as $tag): ?>
                                                    <span class="gene-pill gene-pill--<?= $tag['type'] ?>"><?= htmlspecialchars($tag['label']) ?></span>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php else: ?>
                                            <span class="gene-results__placeholder">Wildtyp</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="gene-results__morph"><?= htmlspecialchars($morphName) ?></span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="gene-results__empty" role="status">
                        <h3>Noch keine Ergebnisse</h3>
                        <p>
                            <?php if ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
                                Bitte wählen Sie mindestens ein Gen mit Träger- oder visueller Ausprägung aus, um Ergebnisse zu erhalten.
                            <?php else: ?>
                                Erfassen Sie die Genetik beider Elternteile und starten Sie die Berechnung.
                            <?php endif; ?>
                        </p>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    <?php endif; ?>
<?php endif; ?>

</section>
<?php include __DIR__ . '/../partials/footer.php'; ?>
