<?php include __DIR__ . '/../partials/header.php'; ?>
<h1>Menüstruktur</h1>
<?php include __DIR__ . '/nav.php'; ?>
<?php if ($flashSuccess): ?>
    <div class="alert alert-success"><?= htmlspecialchars($flashSuccess) ?></div>
<?php endif; ?>
<div class="grid" style="grid-template-columns:2fr 1fr;gap:2rem;align-items:start;">
    <div class="card">
        <h2>Navigationspunkte</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Label</th>
                    <th>Route</th>
                    <th>Position</th>
                    <th>Sichtbar</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $item): ?>
                    <tr>
                        <td><?= htmlspecialchars($item['label']) ?></td>
                        <td>
                            <?php if ($item['route']): ?>Route: <?= htmlspecialchars($item['route']) ?><?php elseif ($item['page_slug']): ?>Seite: <?= htmlspecialchars($item['page_slug']) ?><?php else: ?>Extern<?php endif; ?>
                        </td>
                        <td><?= (int)$item['position'] ?></td>
                        <td><?= $item['is_visible'] ? 'Ja' : 'Nein' ?></td>
                        <td>
                            <a class="btn btn-secondary" href="<?= route_url('admin/menu', ['edit' => $item['id']]) ?>">Bearbeiten</a>
                            <a class="btn btn-secondary" href="<?= route_url('admin/menu', ['delete' => $item['id']]) ?>" onclick="return confirm('Menüpunkt löschen?');">Löschen</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="card">
        <h2>Menüpunkt <?= $editItem ? 'bearbeiten' : 'hinzufügen' ?></h2>
        <form method="post">
            <?php if ($editItem): ?>
                <input type="hidden" name="id" value="<?= (int)$editItem['id'] ?>">
            <?php endif; ?>
            <label>Label
                <input type="text" name="label" value="<?= htmlspecialchars($editItem['label'] ?? '') ?>" required>
            </label>
            <label>Route (z. B. home, genetics)
                <input type="text" name="route" value="<?= htmlspecialchars($editItem['route'] ?? '') ?>">
            </label>
            <label>Seite
                <select name="page_slug">
                    <option value="">– keine –</option>
                    <?php foreach ($pages as $page): ?>
                        <option value="<?= htmlspecialchars($page['slug']) ?>" <?= (($editItem['page_slug'] ?? '') === $page['slug']) ? 'selected' : '' ?>><?= htmlspecialchars($page['title']) ?></option>
                    <?php endforeach; ?>
                </select>
            </label>
            <label>Externe URL
                <input type="url" name="external_url" value="<?= htmlspecialchars($editItem['external_url'] ?? '') ?>">
            </label>
            <label>Position
                <input type="number" name="position" value="<?= htmlspecialchars($editItem['position'] ?? 0) ?>">
            </label>
            <label style="display:flex;align-items:center;gap:0.5rem;">
                <input type="checkbox" name="is_visible" value="1" <?= !empty($editItem['is_visible']) ? 'checked' : '' ?>> Sichtbar
            </label>
            <button type="submit">Speichern</button>
        </form>
    </div>
</div>
<?php include __DIR__ . '/../partials/footer.php'; ?>
