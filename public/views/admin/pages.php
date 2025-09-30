<?php include __DIR__ . '/../partials/header.php'; ?>
<h1>Seiten verwalten</h1>
<?php include __DIR__ . '/nav.php'; ?>
<?php if ($flashSuccess): ?>
    <div class="alert alert-success"><?= htmlspecialchars($flashSuccess) ?></div>
<?php endif; ?>
<div class="grid" style="grid-template-columns:2fr 1fr;gap:2rem;align-items:start;">
    <div class="card">
        <h2>Seiten</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Titel</th>
                    <th>Slug</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pages as $page): ?>
                    <tr>
                        <td><?= htmlspecialchars($page['title']) ?></td>
                        <td><?= htmlspecialchars($page['slug']) ?></td>
                        <td><?= $page['is_published'] ? 'Veröffentlicht' : 'Entwurf' ?></td>
                        <td>
                            <a class="btn btn-secondary" href="<?= route_url('admin/pages', ['edit' => $page['id']]) ?>">Bearbeiten</a>
                            <a class="btn btn-secondary" href="<?= route_url('admin/pages', ['delete' => $page['id']]) ?>" onclick="return confirm('Seite wirklich löschen?');">Löschen</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="card">
        <h2><?= $editPage ? 'Seite bearbeiten' : 'Neue Seite' ?></h2>
        <form method="post">
            <?php if ($editPage): ?>
                <input type="hidden" name="id" value="<?= (int)$editPage['id'] ?>">
            <?php endif; ?>
            <label>Titel
                <input type="text" name="title" value="<?= htmlspecialchars($editPage['title'] ?? '') ?>" required>
            </label>
            <label>Slug
                <input type="text" name="slug" value="<?= htmlspecialchars($editPage['slug'] ?? '') ?>" placeholder="auto-generiert">
            </label>
            <label>Teaser
                <textarea name="excerpt"><?= htmlspecialchars($editPage['excerpt'] ?? '') ?></textarea>
            </label>
            <label>Inhalt (HTML möglich)
                <textarea name="content" rows="10" required><?= htmlspecialchars($editPage['content'] ?? '') ?></textarea>
            </label>
            <label style="display:flex;align-items:center;gap:0.5rem;">
                <input type="checkbox" name="is_published" value="1" <?= !empty($editPage['is_published']) ? 'checked' : '' ?>> Veröffentlicht
            </label>
            <button type="submit">Speichern</button>
        </form>
    </div>
</div>
<?php include __DIR__ . '/../partials/footer.php'; ?>
