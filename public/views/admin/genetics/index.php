<section class="card">
    <h2>Genetikverwaltung</h2>
    <p>Pflege Arten, Gene und deren Visuals. Alle Daten werden in der SQLite-Datenbank gespeichert.</p>
</section>

<section class="card">
    <h3>Arten</h3>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Art</th>
                    <th>Slug</th>
                    <th>Aktionen</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($species as $sp): ?>
                    <tr>
                        <td><?= htmlspecialchars($sp['common_name']) ?> (<?= htmlspecialchars($sp['latin_name']) ?>)</td>
                        <td><?= htmlspecialchars($sp['slug']) ?></td>
                        <td>
                            <a class="button secondary" href="<?= url('admin/genetics', ['species_id' => $sp['id'], 'tab' => 'species']) ?>">Bearbeiten</a>
                            <form method="post" action="<?= url('admin/genetics') ?>" style="display:inline" onsubmit="return confirm('Diese Art inklusive Gene löschen?');">
                                <input type="hidden" name="action" value="delete-species">
                                <input type="hidden" name="id" value="<?= $sp['id'] ?>">
                                <button class="button danger" type="submit">Löschen</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <h4><?= $editSpecies ? 'Art bearbeiten' : 'Neue Art anlegen' ?></h4>
    <form method="post" action="<?= url('admin/genetics') ?>">
        <input type="hidden" name="action" value="save-species">
        <input type="hidden" name="id" value="<?= $editSpecies['id'] ?? '' ?>">
        <label for="species-common">Trivialname</label>
        <input id="species-common" name="common_name" type="text" value="<?= htmlspecialchars($editSpecies['common_name'] ?? '') ?>" required>

        <label for="species-latin">Wissenschaftlicher Name</label>
        <input id="species-latin" name="latin_name" type="text" value="<?= htmlspecialchars($editSpecies['latin_name'] ?? '') ?>" required>

        <label for="species-slug">Slug</label>
        <input id="species-slug" name="slug" type="text" value="<?= htmlspecialchars($editSpecies['slug'] ?? '') ?>">

        <label for="species-description">Beschreibung</label>
        <textarea id="species-description" name="description" required><?= htmlspecialchars($editSpecies['description'] ?? '') ?></textarea>

        <label for="species-habitat">Lebensraum</label>
        <textarea id="species-habitat" name="habitat"><?= htmlspecialchars($editSpecies['habitat'] ?? '') ?></textarea>

        <label for="species-care">Haltungs-Hinweise</label>
        <textarea id="species-care" name="care_notes"><?= htmlspecialchars($editSpecies['care_notes'] ?? '') ?></textarea>

        <button class="button" type="submit"><?= $editSpecies ? 'Speichern' : 'Anlegen' ?></button>
    </form>
</section>

<section class="card">
    <h3>Gene</h3>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Gen</th>
                    <th>Art</th>
                    <th>Vererbung</th>
                    <th>Aktionen</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($genes as $gene): ?>
                    <tr>
                        <td><?= htmlspecialchars($gene['name']) ?></td>
                        <td><?= htmlspecialchars($gene['common_name']) ?></td>
                        <td><span class="badge inheritance-<?= htmlspecialchars($gene['inheritance']) ?>"><?= htmlspecialchars($gene['inheritance']) ?></span></td>
                        <td>
                            <a class="button secondary" href="<?= url('admin/genetics', ['gene_id' => $gene['id'], 'tab' => 'genes']) ?>">Bearbeiten</a>
                            <form method="post" action="<?= url('admin/genetics') ?>" style="display:inline" onsubmit="return confirm('Gen wirklich löschen?');">
                                <input type="hidden" name="action" value="delete-gene">
                                <input type="hidden" name="id" value="<?= $gene['id'] ?>">
                                <button class="button danger" type="submit">Löschen</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <h4><?= $editGene ? 'Gen bearbeiten' : 'Neues Gen anlegen' ?></h4>
    <form method="post" action="<?= url('admin/genetics') ?>">
        <input type="hidden" name="action" value="save-gene">
        <input type="hidden" name="id" value="<?= $editGene['id'] ?? '' ?>">
        <label for="gene-species">Art</label>
        <select id="gene-species" name="species_id" required>
            <option value="">Art wählen …</option>
            <?php foreach ($species as $sp): ?>
                <option value="<?= $sp['id'] ?>" <?= $editGene && $editGene['species_id'] == $sp['id'] ? 'selected' : '' ?>><?= htmlspecialchars($sp['common_name']) ?></option>
            <?php endforeach; ?>
        </select>

        <label for="gene-name">Gen</label>
        <input id="gene-name" name="name" type="text" value="<?= htmlspecialchars($editGene['name'] ?? '') ?>" required>

        <label for="gene-inheritance">Vererbung</label>
        <select id="gene-inheritance" name="inheritance" required>
            <?php $inheritances = ['recessive' => 'Rezessiv', 'co-dominant' => 'Co-dominant', 'dominant' => 'Dominant']; ?>
            <?php foreach ($inheritances as $key => $label): ?>
                <option value="<?= $key ?>" <?= $editGene && $editGene['inheritance'] === $key ? 'selected' : '' ?>><?= $label ?></option>
            <?php endforeach; ?>
        </select>

        <label for="gene-description">Beschreibung</label>
        <textarea id="gene-description" name="description"><?= htmlspecialchars($editGene['description'] ?? '') ?></textarea>

        <label>Visuals</label>
        <input type="text" name="visual_dominant" placeholder="Homozygot/ Dominant" value="<?= htmlspecialchars($editGene['visuals']['dominant'] ?? '') ?>">
        <input type="text" name="visual_heterozygous" placeholder="Heterozygot" value="<?= htmlspecialchars($editGene['visuals']['heterozygous'] ?? '') ?>">
        <input type="text" name="visual_recessive" placeholder="Rezessiv" value="<?= htmlspecialchars($editGene['visuals']['recessive'] ?? '') ?>">

        <button class="button" type="submit"><?= $editGene ? 'Speichern' : 'Anlegen' ?></button>
    </form>
</section>
