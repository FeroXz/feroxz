<section class="card">
    <h2>Seiten</h2>
    <table class="table">
        <thead>
            <tr>
                <th>Titel</th>
                <th>Slug</th>
                <th>Zuletzt aktualisiert</th>
                <th>Aktionen</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($pagesList as $page): ?>
                <tr>
                    <td><?= htmlspecialchars($page['title']) ?></td>
                    <td><?= htmlspecialchars($page['slug']) ?></td>
                    <td><?= date('d.m.Y', strtotime($page['updated_at'])) ?></td>
                    <td>
                        <a class="button secondary" href="<?= url('admin/pages', ['id' => $page['id']]) ?>">Bearbeiten</a>
                        <form method="post" action="<?= url('admin/pages') ?>" style="display:inline" onsubmit="return confirm('Diese Seite wirklich löschen?');">
                            <input type="hidden" name="id" value="<?= $page['id'] ?>">
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
    <h2><?= $editPage ? 'Seite bearbeiten' : 'Neue Seite erstellen' ?></h2>
    <form method="post" action="<?= url('admin/pages') ?>">
        <input type="hidden" name="id" value="<?= $editPage['id'] ?? '' ?>">
        <label for="title">Titel</label>
        <input id="title" name="title" type="text" value="<?= htmlspecialchars($editPage['title'] ?? '') ?>" required>

        <label for="slug">Slug</label>
        <input id="slug" name="slug" type="text" value="<?= htmlspecialchars($editPage['slug'] ?? '') ?>">

        <label for="content">Inhalt</label>
        <textarea id="content" name="content" required><?= htmlspecialchars($editPage['content'] ?? '') ?></textarea>

        <button class="button" type="submit"><?= $editPage ? 'Speichern' : 'Erstellen' ?></button>
    </form>
</section>
