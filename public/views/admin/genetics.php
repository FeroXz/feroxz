<?php include __DIR__ . '/../partials/header.php'; ?>
<h1>Genetikdatenbank</h1>
<?php include __DIR__ . '/nav.php'; ?>
<?php if ($flashSuccess): ?>
    <div class="alert alert-success"><?= htmlspecialchars($flashSuccess) ?></div>
<?php endif; ?>
<div class="grid" style="grid-template-columns:1fr 2fr;gap:2rem;align-items:start;">
    <div class="card">
        <h2>Spezies</h2>
        <ul>
            <?php foreach ($speciesList as $entry): ?>
                <li>
                    <a href="<?= route_url('admin/genetics', ['species' => $entry['id']]) ?>" class="<?= ($selectedSpecies && $selectedSpecies['id'] == $entry['id']) ? 'active-link' : '' ?>">
                        <?= htmlspecialchars($entry['name']) ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
        <?php if ($selectedSpecies): ?>
            <form method="post" style="margin-top:1.5rem;">
                <input type="hidden" name="update_species" value="1">
                <input type="hidden" name="species_id" value="<?= (int)$selectedSpecies['id'] ?>">
                <label>Name
                    <input type="text" name="name" value="<?= htmlspecialchars($selectedSpecies['name']) ?>">
                </label>
                <label>Wissenschaftlicher Name
                    <input type="text" name="scientific_name" value="<?= htmlspecialchars($selectedSpecies['scientific_name']) ?>">
                </label>
                <label>Beschreibung
                    <textarea name="description" rows="4"><?= htmlspecialchars($selectedSpecies['description']) ?></textarea>
                </label>
                <button type="submit">Spezies aktualisieren</button>
            </form>
        <?php endif; ?>
    </div>
    <div class="card">
        <?php if (!$selectedSpecies): ?>
            <p>Bitte eine Spezies auswählen.</p>
        <?php else: ?>
            <h2>Gene für <?= htmlspecialchars($selectedSpecies['name']) ?></h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Vererbung</th>
                        <th>Visual</th>
                        <th>Het</th>
                        <th>Super</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($genes as $gene): ?>
                        <tr>
                            <td><?= htmlspecialchars($gene['name']) ?></td>
                            <td><?= htmlspecialchars($gene['inheritance']) ?></td>
                            <td><?= htmlspecialchars($gene['visual_label']) ?></td>
                            <td><?= htmlspecialchars($gene['heterozygous_label']) ?></td>
                            <td><?= htmlspecialchars($gene['homozygous_label']) ?></td>
                            <td>
                                <a class="btn btn-secondary" href="<?= route_url('admin/genetics', ['species' => $selectedSpecies['id'], 'edit' => $gene['id']]) ?>">Bearbeiten</a>
                                <a class="btn btn-secondary" href="<?= route_url('admin/genetics', ['species' => $selectedSpecies['id'], 'delete' => $gene['id']]) ?>" onclick="return confirm('Gen löschen?');">Löschen</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <hr style="margin:2rem 0;border-color:rgba(148,163,184,0.2);">
            <h3><?= $editGene ? 'Gen bearbeiten' : 'Neues Gen' ?></h3>
            <form method="post">
                <?php if ($editGene): ?>
                    <input type="hidden" name="id" value="<?= (int)$editGene['id'] ?>">
                <?php endif; ?>
                <input type="hidden" name="species_id" value="<?= (int)$selectedSpecies['id'] ?>">
                <label>Name
                    <input type="text" name="name" value="<?= htmlspecialchars($editGene['name'] ?? '') ?>" required>
                </label>
                <label>Slug
                    <input type="text" name="slug" value="<?= htmlspecialchars($editGene['slug'] ?? '') ?>" placeholder="auto-generiert">
                </label>
                <label>Vererbung
                    <select name="inheritance">
                        <?php foreach (['recessive' => 'rezessiv', 'codominant' => 'codominant', 'dominant' => 'dominant'] as $value => $label): ?>
                            <option value="<?= $value ?>" <?= (($editGene['inheritance'] ?? '') === $value) ? 'selected' : '' ?>><?= $label ?></option>
                        <?php endforeach; ?>
                    </select>
                </label>
                <label>Beschreibung
                    <textarea name="description" rows="4"><?= htmlspecialchars($editGene['description'] ?? '') ?></textarea>
                </label>
                <label>Visual Label
                    <input type="text" name="visual_label" value="<?= htmlspecialchars($editGene['visual_label'] ?? '') ?>">
                </label>
                <label>Het Label
                    <input type="text" name="heterozygous_label" value="<?= htmlspecialchars($editGene['heterozygous_label'] ?? '') ?>">
                </label>
                <label>Super Label
                    <input type="text" name="homozygous_label" value="<?= htmlspecialchars($editGene['homozygous_label'] ?? '') ?>">
                </label>
                <label>Wildtyp Label
                    <input type="text" name="wild_label" value="<?= htmlspecialchars($editGene['wild_label'] ?? 'Wildtyp') ?>">
                </label>
                <button type="submit">Speichern</button>
            </form>
        <?php endif; ?>
    </div>
</div>
<?php include __DIR__ . '/../partials/footer.php'; ?>
