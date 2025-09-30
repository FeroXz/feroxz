<?php include __DIR__ . '/../partials/header.php'; ?>
<h1>Galerie verwalten</h1>
<?php include __DIR__ . '/nav.php'; ?>
<?php if ($flashSuccess): ?>
    <div class="alert alert-success"><?= htmlspecialchars($flashSuccess) ?></div>
<?php endif; ?>
<div class="grid" style="grid-template-columns:2fr 1fr;gap:2rem;align-items:start;">
    <div class="card">
        <h2>Medien</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Titel</th>
                    <th>Bild</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $item): ?>
                    <tr>
                        <td><?= htmlspecialchars($item['title']) ?></td>
                        <td><?php if (!empty($item['image_path'])): ?><a href="<?= BASE_URL . '/' . htmlspecialchars($item['image_path']) ?>" target="_blank">Anzeigen</a><?php endif; ?></td>
                        <td>
                            <a class="btn btn-secondary" href="<?= route_url('admin/gallery', ['edit' => $item['id']]) ?>">Bearbeiten</a>
                            <a class="btn btn-secondary" href="<?= route_url('admin/gallery', ['delete' => $item['id']]) ?>" onclick="return confirm('Eintrag löschen?');">Löschen</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="card">
        <h2><?= $editItem ? 'Eintrag bearbeiten' : 'Neuer Eintrag' ?></h2>
        <form method="post" enctype="multipart/form-data">
            <?php if ($editItem): ?>
                <input type="hidden" name="id" value="<?= (int)$editItem['id'] ?>">
            <?php endif; ?>
            <label>Titel
                <input type="text" name="title" value="<?= htmlspecialchars($editItem['title'] ?? '') ?>" required>
            </label>
            <label>Beschreibung
                <textarea name="description"><?= htmlspecialchars($editItem['description'] ?? '') ?></textarea>
            </label>
            <label>Bild
                <input type="file" name="image" accept="image/*">
                <?php if (!empty($editItem['image_path'])): ?>
                    <input type="hidden" name="image_path" value="<?= htmlspecialchars($editItem['image_path']) ?>">
                    <p><a href="<?= BASE_URL . '/' . htmlspecialchars($editItem['image_path']) ?>" target="_blank">Aktuelles Bild anzeigen</a></p>
                <?php endif; ?>
            </label>
            <button type="submit">Speichern</button>
        </form>
    </div>
</div>
<?php include __DIR__ . '/../partials/footer.php'; ?>
