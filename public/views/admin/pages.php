<?php include __DIR__ . '/../partials/header.php'; ?>
<section class="admin-shell">
<header class="admin-page-header">
    <div>
        <h1 class="admin-title">Seiten verwalten</h1>
        <p class="admin-subtitle">Strukturiere Inhalte wie ein modularer Felsgarten – klar gegliedert und perfekt für neugierige Bartagamen-Fans.</p>
    </div>
    <div class="admin-meta">
        <span class="badge">Informationsnetz</span>
        <span><?= count($pages) ?> Seiten</span>
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
<?php
    $pageTitleLookup = [];
    foreach ($pages as $pageRow) {
        $pageTitleLookup[$pageRow['id']] = $pageRow['title'];
    }
    $hasPagePost = ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['title']) && isset($_POST['content']));
    $formValues = [
        'title' => $hasPagePost ? ($_POST['title'] ?? '') : ($editPage['title'] ?? ''),
        'slug' => $hasPagePost ? ($_POST['slug'] ?? '') : ($editPage['slug'] ?? ''),
        'content' => $hasPagePost ? ($_POST['content'] ?? '') : ($editPage['content'] ?? ''),
        'is_published' => $hasPagePost ? !empty($_POST['is_published']) : !empty($editPage['is_published'] ?? null),
        'show_in_menu' => $hasPagePost ? !empty($_POST['show_in_menu']) : !empty($editPage['show_in_menu'] ?? null),
        'parent_id' => $hasPagePost ? ($_POST['parent_id'] ?? '') : (($editPage['parent_id'] ?? '') !== '' ? (string)$editPage['parent_id'] : ''),
    ];
?>
<div class="admin-layout">
    <div class="card">
        <h2>Bestehende Seiten</h2>
        <div class="table-responsive">
            <table class="table">
            <thead>
                <tr>
                    <th>Titel</th>
                    <th>Slug</th>
                    <th>Status</th>
                    <th>Menü</th>
                    <th>Übergeordnet</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pages as $pageItem): ?>
                    <tr>
                        <td><?= htmlspecialchars($pageItem['title']) ?></td>
                        <td><?= htmlspecialchars($pageItem['slug']) ?></td>
                        <td>
                            <?php if ($pageItem['is_published']): ?>
                                <span class="badge">veröffentlicht</span>
                            <?php else: ?>
                                <span class="badge">Entwurf</span>
                            <?php endif; ?>
                        </td>
                        <td><?= !empty($pageItem['show_in_menu']) ? 'sichtbar' : 'ausgeblendet' ?></td>
                        <td>
                            <?php if (!empty($pageItem['parent_id']) && isset($pageTitleLookup[$pageItem['parent_id']])): ?>
                                <?= htmlspecialchars($pageTitleLookup[$pageItem['parent_id']]) ?>
                            <?php else: ?>
                                <span class="text-muted">Hauptebene</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a class="btn btn-secondary" href="<?= BASE_URL ?>/index.php?route=admin/pages&edit=<?= (int)$pageItem['id'] ?>">Bearbeiten</a>
                            <a class="btn btn-secondary" href="<?= BASE_URL ?>/index.php?route=admin/pages&delete=<?= (int)$pageItem['id'] ?>" onclick="return confirm('Seite wirklich löschen?');">Löschen</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            </table>
        </div>
    </div>
    <div class="card">
        <h2><?= $editPage ? 'Seite bearbeiten' : 'Neue Seite' ?></h2>
        <form method="post">
            <?php if ($editPage): ?>
                <input type="hidden" name="id" value="<?= (int)$editPage['id'] ?>">
            <?php endif; ?>
            <label>Titel
                <input type="text" name="title" value="<?= htmlspecialchars($formValues['title']) ?>" required>
            </label>
            <label>Slug (optional)
                <input type="text" name="slug" value="<?= htmlspecialchars($formValues['slug']) ?>" placeholder="automatisch aus Titel">
            </label>
            <label>Inhalt
                <textarea name="content" class="rich-text" required><?= htmlspecialchars($formValues['content']) ?></textarea>
            </label>
            <label class="form-switch">
                <input type="checkbox" name="is_published" value="1" <?= !empty($formValues['is_published']) ? 'checked' : '' ?>>
                <span>veröffentlichen</span>
            </label>
            <label class="form-switch">
                <input type="checkbox" name="show_in_menu" value="1" <?= !empty($formValues['show_in_menu']) ? 'checked' : '' ?>>
                <span>Im Hauptmenü anzeigen</span>
            </label>
            <label>Übergeordnete Seite
                <select name="parent_id">
                    <option value="">Keine (Hauptebene)</option>
                    <?php foreach ($pages as $parentOption): ?>
                        <?php if ($editPage && (int)$parentOption['id'] === (int)$editPage['id']): ?>
                            <?php continue; ?>
                        <?php endif; ?>
                        <option value="<?= (int)$parentOption['id'] ?>" <?= ((string)$parentOption['id'] === (string)$formValues['parent_id']) ? 'selected' : '' ?>><?= htmlspecialchars($parentOption['title']) ?></option>
                    <?php endforeach; ?>
                </select>
            </label>
            <button type="submit">Speichern</button>
        </form>
    </div>
</div>
</div>
</section>
<?php include __DIR__ . '/../partials/footer.php'; ?>

