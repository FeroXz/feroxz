<section class="card">
    <h2>Tiere verwalten</h2>
    <?php $isEditing = $editAnimal !== null; ?>
    <div class="grid">
        <div class="card">
            <h3><?= $isEditing ? 'Tier bearbeiten' : 'Neues Tier anlegen' ?></h3>
            <form method="post" action="<?= url('admin/animals') ?>" enctype="multipart/form-data">
                <input type="hidden" name="action" value="save-animal">
                <input type="hidden" name="id" value="<?= $isEditing ? (int)$editAnimal['id'] : '' ?>">
                <label for="animal-name">Name</label>
                <input id="animal-name" type="text" name="name" value="<?= $isEditing ? htmlspecialchars($editAnimal['name']) : '' ?>" required>

                <label for="animal-species">Art</label>
                <select id="animal-species" name="species_id" required>
                    <option value="">Bitte wählen</option>
                    <?php foreach ($species as $sp): ?>
                        <option value="<?= $sp['id'] ?>" <?= $isEditing && (int)$editAnimal['species_id'] === (int)$sp['id'] ? 'selected' : '' ?>><?= htmlspecialchars($sp['common_name']) ?> (<?= htmlspecialchars($sp['latin_name']) ?>)</option>
                    <?php endforeach; ?>
                </select>

                <label for="animal-owner">Besitzer</label>
                <select id="animal-owner" name="owner_id">
                    <option value="">Standard (aktueller Benutzer)</option>
                    <?php foreach ($users as $user): ?>
                        <option value="<?= $user['id'] ?>" <?= $isEditing && (int)($editAnimal['owner_id'] ?? 0) === (int)$user['id'] ? 'selected' : '' ?>><?= htmlspecialchars($user['username']) ?></option>
                    <?php endforeach; ?>
                </select>

                <label for="animal-age">Alter</label>
                <input id="animal-age" type="text" name="age" value="<?= $isEditing ? htmlspecialchars($editAnimal['age'] ?? '') : '' ?>" placeholder="z. B. 2 Jahre">

                <label for="animal-origin">Herkunft</label>
                <input id="animal-origin" type="text" name="origin" value="<?= $isEditing ? htmlspecialchars($editAnimal['origin'] ?? '') : '' ?>" placeholder="Züchter, Fundort ...">

                <label for="animal-genetics-notes">Genetik-Notizen</label>
                <textarea id="animal-genetics-notes" name="genetics_notes" rows="3" placeholder="Besondere Linien, Eltern, Nachweise ..."><?= $isEditing ? htmlspecialchars($editAnimal['genetics_notes'] ?? '') : '' ?></textarea>

                <label for="animal-special-notes">Besonderheiten</label>
                <textarea id="animal-special-notes" name="special_notes" rows="3" placeholder="Charakter, Gesundheit, Bemerkungen ..."><?= $isEditing ? htmlspecialchars($editAnimal['special_notes'] ?? '') : '' ?></textarea>

                <label class="checkbox-field">
                    <input type="checkbox" name="is_showcased" value="1" <?= $isEditing ? (!empty($editAnimal['is_showcased']) ? 'checked' : '') : '' ?>>
                    <span>In der öffentlichen Tierübersicht anzeigen</span>
                </label>

                <label class="checkbox-field">
                    <input type="checkbox" name="is_private" value="1" <?= $isEditing ? (!empty($editAnimal['is_private']) ? 'checked' : '') : '' ?>>
                    <span>Nur für den Besitzer sichtbar halten</span>
                </label>

                <fieldset>
                    <legend>Genetik</legend>
                    <p class="help-text">Wähle die passenden Genotypen aus. Nicht ausgewählte Gene werden als Standard interpretiert.</p>
                    <?php foreach ($species as $sp): ?>
                        <?php $genesForSpecies = $genesBySpecies[$sp['id']] ?? []; ?>
                        <div class="gene-group" data-species="<?= $sp['id'] ?>" <?= $isEditing && (int)$editAnimal['species_id'] === (int)$sp['id'] ? '' : 'hidden' ?>>
                            <?php if (empty($genesForSpecies)): ?>
                                <p class="help-text">Für diese Art sind noch keine Gene hinterlegt.</p>
                            <?php else: ?>
                                <?php foreach ($genesForSpecies as $gene): ?>
                                    <?php $selected = $editGenotypes[$gene['id']] ?? defaultGenotypeForInheritance($gene['inheritance']); ?>
                                    <label for="gene-<?= $gene['id'] ?>"><?= htmlspecialchars($gene['name']) ?> <span class="badge inheritance-<?= htmlspecialchars($gene['inheritance']) ?>"><?= htmlspecialchars($gene['inheritance']) ?></span></label>
                                    <select id="gene-<?= $gene['id'] ?>" name="genotypes[<?= $gene['id'] ?>]">
                                        <option value="">Standard</option>
                                        <?php foreach (genotypeOptions($gene['inheritance']) as $value => $label): ?>
                                            <option value="<?= $value ?>" <?= $selected === $value ? 'selected' : '' ?>><?= htmlspecialchars($label) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </fieldset>

                <label for="animal-images">Bilder hochladen</label>
                <input id="animal-images" type="file" name="images[]" accept="image/*" multiple>

                <?php if (!empty($editImages)): ?>
                    <div class="image-grid">
                        <?php foreach ($editImages as $image): ?>
                            <label class="image-tile">
                                <input type="checkbox" name="remove_images[]" value="<?= $image['id'] ?>">
                                <span class="image-preview" style="background-image: url('<?= htmlspecialchars($image['image_path']) ?>');"></span>
                                <span class="image-caption">Bild entfernen</span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <button class="button" type="submit">Speichern</button>
                <?php if ($isEditing): ?>
                    <a class="button secondary" href="<?= url('admin/animals') ?>">Abbrechen</a>
                <?php endif; ?>
            </form>
        </div>

        <div class="card">
            <h3>Deine Tiere</h3>
            <?php if (empty($animals)): ?>
                <p>Noch keine Tiere eingetragen.</p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table-modern">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Art</th>
                                <th>Alter</th>
                                <th>Besitzer</th>
                                <th>Sichtbarkeit</th>
                                <th>Genetik</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($animals as $animal): ?>
                                <?php $genesForSpecies = $genesBySpecies[$animal['species_id']] ?? []; ?>
                                <?php $genotypeMap = buildAnimalGenotypeMap($genesForSpecies, $animal['genotypes'] ?? []); ?>
                                <tr>
                                    <td><?= htmlspecialchars($animal['name']) ?></td>
                                    <td><?= htmlspecialchars($animal['common_name']) ?></td>
                                    <td><?= htmlspecialchars(isset($animal['age']) && $animal['age'] !== '' ? $animal['age'] : '–') ?></td>
                                    <td><?= htmlspecialchars($animal['owner_username'] ?? '–') ?></td>
                                    <td>
                                        <?php if (!empty($animal['is_private'])): ?>
                                            <span class="badge warning">Privat</span>
                                        <?php else: ?>
                                            <span class="badge info">Intern</span>
                                            <?php if (!empty($animal['is_showcased'])): ?>
                                                <span class="badge success">Showcase</span>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= htmlspecialchars(summarizeGeneStates($genesForSpecies, $genotypeMap)) ?></td>
                                    <td class="actions">
                                        <a class="button small" href="<?= url('admin/animals', ['id' => $animal['id']]) ?>">Bearbeiten</a>
                                        <form method="post" action="<?= url('admin/animals') ?>" onsubmit="return confirm('Tier wirklich löschen?');">
                                            <input type="hidden" name="action" value="delete-animal">
                                            <input type="hidden" name="id" value="<?= $animal['id'] ?>">
                                            <button class="button danger small" type="submit">Löschen</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<script>
    (function () {
        const speciesSelect = document.getElementById('animal-species');
        const groups = document.querySelectorAll('.gene-group');
        function updateGroups() {
            const selected = speciesSelect.value;
            groups.forEach(group => {
                if (!selected) {
                    group.hidden = true;
                    return;
                }
                group.hidden = group.dataset.species !== selected;
            });
        }
        const showcaseToggle = document.querySelector('input[name="is_showcased"]');
        const privateToggle = document.querySelector('input[name="is_private"]');
        function updateVisibilityState() {
            if (!showcaseToggle || !privateToggle) {
                return;
            }
            if (privateToggle.checked) {
                showcaseToggle.checked = false;
                showcaseToggle.disabled = true;
            } else {
                showcaseToggle.disabled = false;
            }
        }
        if (speciesSelect) {
            speciesSelect.addEventListener('change', updateGroups);
            updateGroups();
        }
        if (privateToggle) {
            privateToggle.addEventListener('change', updateVisibilityState);
            updateVisibilityState();
        }
    }());
</script>
