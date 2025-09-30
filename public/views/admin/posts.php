<?php include __DIR__ . '/../partials/header.php'; ?>
<h1>Beiträge verwalten</h1>
<?php include __DIR__ . '/nav.php'; ?>
<?php if ($flashSuccess): ?>
    <div class="alert alert-success"><?= htmlspecialchars($flashSuccess) ?></div>
<?php endif; ?>
<div class="grid" style="grid-template-columns:2fr 1fr;gap:2rem;align-items:start;">
    <div class="card">
        <h2>Journal</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Titel</th>
                    <th>Slug</th>
                    <th>Veröffentlicht</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($posts as $post): ?>
                    <tr>
                        <td><?= htmlspecialchars($post['title']) ?></td>
                        <td><?= htmlspecialchars($post['slug']) ?></td>
                        <td><?= $post['published_at'] ? date('d.m.Y', strtotime($post['published_at'])) : 'Entwurf' ?></td>
                        <td>
                            <a class="btn btn-secondary" href="<?= route_url('admin/posts', ['edit' => $post['id']]) ?>">Bearbeiten</a>
                            <a class="btn btn-secondary" href="<?= route_url('admin/posts', ['delete' => $post['id']]) ?>" onclick="return confirm('Beitrag löschen?');">Löschen</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="card">
        <h2><?= $editPost ? 'Beitrag bearbeiten' : 'Neuer Beitrag' ?></h2>
        <form method="post">
            <?php if ($editPost): ?>
                <input type="hidden" name="id" value="<?= (int)$editPost['id'] ?>">
            <?php endif; ?>
            <label>Titel
                <input type="text" name="title" value="<?= htmlspecialchars($editPost['title'] ?? '') ?>" required>
            </label>
            <label>Slug
                <input type="text" name="slug" value="<?= htmlspecialchars($editPost['slug'] ?? '') ?>" placeholder="auto-generiert">
            </label>
            <label>Teaser
                <textarea name="excerpt"><?= htmlspecialchars($editPost['excerpt'] ?? '') ?></textarea>
            </label>
            <label>Inhalt
                <textarea name="content" rows="10" required><?= htmlspecialchars($editPost['content'] ?? '') ?></textarea>
            </label>
            <label>Veröffentlichungsdatum
                <input type="datetime-local" name="published_at" value="<?= !empty($editPost['published_at']) ? date('Y-m-d\TH:i', strtotime($editPost['published_at'])) : '' ?>">
            </label>
            <label style="display:flex;align-items:center;gap:0.5rem;">
                <input type="checkbox" name="is_published" value="1" <?= !empty($editPost['published_at']) ? 'checked' : '' ?>> Veröffentlichen
            </label>
            <button type="submit">Speichern</button>
        </form>
    </div>
</div>
<?php include __DIR__ . '/../partials/footer.php'; ?>
