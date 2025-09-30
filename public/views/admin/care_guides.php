<?php include __DIR__ . '/../partials/header.php'; ?>
<h1>Pflegeleitfäden verwalten</h1>
<?php include __DIR__ . '/nav.php'; ?>
<?php if ($flashSuccess): ?>
    <div class="alert alert-success"><?= htmlspecialchars($flashSuccess) ?></div>
<?php endif; ?>
<div class="grid" style="grid-template-columns:2fr 1fr;gap:2rem;align-items:start;">
    <div class="card">
        <h2>Leitfäden</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Art</th>
                    <th>Slug</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($guides as $guide): ?>
                    <tr>
                        <td><?= htmlspecialchars($guide['species']) ?></td>
                        <td><?= htmlspecialchars($guide['slug']) ?></td>
                        <td>
                            <a class="btn btn-secondary" href="<?= route_url('admin/care-guides', ['edit' => $guide['id']]) ?>">Bearbeiten</a>
                            <a class="btn btn-secondary" href="<?= route_url('admin/care-guides', ['delete' => $guide['id']]) ?>" onclick="return confirm('Leitfaden löschen?');">Löschen</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="card">
        <h2><?= $editGuide ? 'Leitfaden bearbeiten' : 'Neuer Leitfaden' ?></h2>
        <form method="post">
            <?php if ($editGuide): ?>
                <input type="hidden" name="id" value="<?= (int)$editGuide['id'] ?>">
            <?php endif; ?>
            <label>Art
                <input type="text" name="species" value="<?= htmlspecialchars($editGuide['species'] ?? '') ?>" required>
            </label>
            <label>Slug
                <input type="text" name="slug" value="<?= htmlspecialchars($editGuide['slug'] ?? '') ?>" placeholder="auto-generiert">
            </label>
            <label>Headline
                <input type="text" name="headline" value="<?= htmlspecialchars($editGuide['headline'] ?? '') ?>" required>
            </label>
            <label>Zusammenfassung
                <textarea name="summary"><?= htmlspecialchars($editGuide['summary'] ?? '') ?></textarea>
            </label>
            <label>Habitat
                <textarea name="habitat" rows="3"><?= htmlspecialchars($editGuide['habitat'] ?? '') ?></textarea>
            </label>
            <label>Beleuchtung & Klima
                <textarea name="lighting" rows="3"><?= htmlspecialchars($editGuide['lighting'] ?? '') ?></textarea>
            </label>
            <label>Ernährung
                <textarea name="diet" rows="3"><?= htmlspecialchars($editGuide['diet'] ?? '') ?></textarea>
            </label>
            <label>Enrichment
                <textarea name="enrichment" rows="3"><?= htmlspecialchars($editGuide['enrichment'] ?? '') ?></textarea>
            </label>
            <label>Gesundheit
                <textarea name="health" rows="3"><?= htmlspecialchars($editGuide['health'] ?? '') ?></textarea>
            </label>
            <label>Zucht
                <textarea name="breeding" rows="3"><?= htmlspecialchars($editGuide['breeding'] ?? '') ?></textarea>
            </label>
            <button type="submit">Speichern</button>
        </form>
    </div>
</div>
<?php include __DIR__ . '/../partials/footer.php'; ?>
