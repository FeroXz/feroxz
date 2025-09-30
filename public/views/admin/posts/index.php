<section class="card">
    <h2>Beiträge</h2>
    <div class="table-responsive">
        <table class="table">
            <thead>
            <tr>
                <th>Titel</th>
                <th>Slug</th>
                <th>Veröffentlicht</th>
                <th>Aktionen</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($posts as $post): ?>
                <tr>
                    <td><?= htmlspecialchars($post['title']) ?></td>
                    <td><?= htmlspecialchars($post['slug']) ?></td>
                    <td><?= date('d.m.Y', strtotime($post['published_at'])) ?></td>
                    <td>
                        <a class="button secondary" href="<?= url('admin/posts', ['id' => $post['id']]) ?>">Bearbeiten</a>
                        <form method="post" action="<?= url('admin/posts') ?>" style="display:inline" onsubmit="return confirm('Diesen Beitrag wirklich löschen?');">
                            <input type="hidden" name="id" value="<?= $post['id'] ?>">
                            <input type="hidden" name="action" value="delete">
                            <button class="button danger" type="submit">Löschen</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>

<section class="card">
    <h2><?= $editPost ? 'Beitrag bearbeiten' : 'Neuen Beitrag erstellen' ?></h2>
    <form method="post" action="<?= url('admin/posts') ?>">
        <input type="hidden" name="id" value="<?= $editPost['id'] ?? '' ?>">
        <label for="title">Titel</label>
        <input id="title" name="title" type="text" value="<?= htmlspecialchars($editPost['title'] ?? '') ?>" required>

        <label for="slug">Slug</label>
        <input id="slug" name="slug" type="text" value="<?= htmlspecialchars($editPost['slug'] ?? '') ?>">

        <label for="excerpt">Kurzbeschreibung</label>
        <textarea id="excerpt" name="excerpt"><?= htmlspecialchars($editPost['excerpt'] ?? '') ?></textarea>

        <label for="content">Inhalt</label>
        <textarea id="content" name="content" required><?= htmlspecialchars($editPost['content'] ?? '') ?></textarea>

        <button class="button" type="submit"><?= $editPost ? 'Speichern' : 'Erstellen' ?></button>
    </form>
</section>
