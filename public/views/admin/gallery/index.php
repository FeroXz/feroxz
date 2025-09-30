<section class="card">
    <h2>Galerie</h2>
    <table class="table">
        <thead>
            <tr>
                <th>Titel</th>
                <th>Bild</th>
                <th>Erstellt</th>
                <th>Aktionen</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($items as $item): ?>
                <tr>
                    <td><?= htmlspecialchars($item['title']) ?></td>
                    <td><?php if ($item['image_path']): ?><a href="<?= htmlspecialchars($item['image_path']) ?>" target="_blank">Öffnen</a><?php endif; ?></td>
                    <td><?= date('d.m.Y', strtotime($item['created_at'])) ?></td>
                    <td>
                        <a class="button secondary" href="<?= url('admin/gallery', ['id' => $item['id']]) ?>">Bearbeiten</a>
                        <form method="post" action="<?= url('admin/gallery') ?>" style="display:inline" onsubmit="return confirm('Diesen Eintrag wirklich löschen?');">
                            <input type="hidden" name="id" value="<?= $item['id'] ?>">
                            <input type="hidden" name="action" value="delete">
                            <button class="button danger" type="submit">Löschen</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</section>

<section class="card">
    <h2><?= $editItem ? 'Galerie-Eintrag bearbeiten' : 'Neuen Galerie-Eintrag erstellen' ?></h2>
    <form method="post" action="<?= url('admin/gallery') ?>" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= $editItem['id'] ?? '' ?>">
        <label for="title">Titel</label>
        <input id="title" name="title" type="text" value="<?= htmlspecialchars($editItem['title'] ?? '') ?>" required>

        <label for="description">Beschreibung</label>
        <textarea id="description" name="description"><?= htmlspecialchars($editItem['description'] ?? '') ?></textarea>

        <label for="image_url">Bild-URL (optional)</label>
        <input id="image_url" name="image_url" type="text" value="<?= htmlspecialchars($editItem['image_path'] ?? '') ?>">

        <label for="image">Bild hochladen (alternativ)</label>
        <input id="image" name="image" type="file" accept="image/*">

        <button class="button" type="submit"><?= $editItem ? 'Speichern' : 'Erstellen' ?></button>
    </form>
</section>
