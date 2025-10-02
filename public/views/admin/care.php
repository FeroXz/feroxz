<?php include __DIR__ . '/../partials/header.php'; ?>
<section class="admin-shell">
<header class="admin-page-header">
    <div>
        <h1 class="admin-title">Pflegeleitfaden</h1>
        <p class="admin-subtitle">Sammle und strukturier Wissen für gesunde Pogona vitticeps – klar, leichtgewichtig und jederzeit abrufbar.</p>
    </div>
    <div class="admin-meta">
        <span class="badge">Wissensarchiv</span>
        <span><?= count($careArticles) ?> Artikel</span>
    </div>
</header>
<?php include __DIR__ . '/nav.php'; ?>
<div class="admin-section">
<?php if ($flashSuccess): ?>
    <div class="alert alert-success" role="status" aria-live="polite"><?= htmlspecialchars($flashSuccess) ?></div>
<?php endif; ?>
<?php if ($flashError): ?>
    <div class="alert alert-error" role="alert" aria-live="assertive"><?= htmlspecialchars($flashError) ?></div>
<?php endif; ?>
<div class="admin-layout">
    <div class="card">
        <h2>Artikelübersicht</h2>
        <div class="table-responsive">
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
                <?php foreach ($careArticles as $article): ?>
                    <tr>
                        <td><?= htmlspecialchars($article['title']) ?></td>
                        <td><?= htmlspecialchars($article['slug']) ?></td>
                        <td>
                            <?php if ($article['is_published']): ?>
                                <span class="badge">veröffentlicht</span>
                            <?php else: ?>
                                <span class="badge">Entwurf</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a class="btn btn-secondary" href="<?= BASE_URL ?>/index.php?route=admin/care&edit=<?= (int)$article['id'] ?>">Bearbeiten</a>
                            <a class="btn btn-secondary" href="<?= BASE_URL ?>/index.php?route=admin/care&delete=<?= (int)$article['id'] ?>" onclick="return confirm('Artikel wirklich löschen?');">Löschen</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            </table>
        </div>
    </div>
    <div class="card">
        <h2><?= $editArticle ? 'Artikel bearbeiten' : 'Neuer Artikel' ?></h2>
        <form method="post">
            <?php if ($editArticle): ?>
                <input type="hidden" name="id" value="<?= (int)$editArticle['id'] ?>">
            <?php endif; ?>
            <label>Titel
                <input type="text" name="title" value="<?= htmlspecialchars($editArticle['title'] ?? '') ?>" required>
            </label>
            <label>Slug (optional)
                <input type="text" name="slug" value="<?= htmlspecialchars($editArticle['slug'] ?? '') ?>">
            </label>
            <label>Kurzbeschreibung
                <textarea name="summary" class="rich-text" rows="4"><?= htmlspecialchars($editArticle['summary'] ?? '') ?></textarea>
            </label>
            <label>Inhalt
                <textarea name="content" class="rich-text" required><?= htmlspecialchars($editArticle['content'] ?? '') ?></textarea>
            </label>
            <label class="form-switch">
                <input type="checkbox" name="is_published" value="1" <?= !empty($editArticle['is_published']) ? 'checked' : '' ?>>
                <span>Veröffentlichen</span>
            </label>
            <button type="submit">Speichern</button>
        </form>
    </div>
</div>
</div>
</section>
<?php include __DIR__ . '/../partials/footer.php'; ?>

