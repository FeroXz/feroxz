<?php
if (!function_exists('renderPageTreeRows')) {
    function renderPageTreeRows(array $nodes, array $parentOptions, int $depth = 0): void
    {
        foreach ($nodes as $node) {
            $visible = (int)($node['is_visible'] ?? 0) === 1;
            $padding = max($depth * 1.5, 0);
            ?>
            <tr>
                <td>
                    <span class="tree-label" style="padding-left: <?= number_format($padding, 2) ?>rem;">
                        <?= htmlspecialchars($node['title']) ?>
                    </span>
                </td>
                <td class="table-monospace">/<?= htmlspecialchars($node['slug']) ?></td>
                <td><?= $visible ? 'Im Menü' : 'Ausgeblendet' ?></td>
                <td>
                    <form method="post" action="<?= url('admin/pages') ?>" class="inline-form">
                        <input type="hidden" name="action" value="update-menu">
                        <input type="hidden" name="id" value="<?= (int)$node['id'] ?>">
                        <label>
                            Reihenfolge
                            <input type="number" name="menu_order" value="<?= (int)$node['menu_order'] ?>">
                        </label>
                        <label>
                            Übergeordnet
                            <select name="parent_id">
                                <option value="">(Keine)</option>
                                <?php foreach ($parentOptions as $option): ?>
                                    <?php if ((int)$option['id'] === (int)$node['id']) { continue; } ?>
                                    <?php
                                    $indent = str_repeat('— ', (int)($option['depth'] ?? 0));
                                    $selected = ((int)($node['parent_id'] ?? 0) === (int)$option['id']) ? 'selected' : '';
                                    ?>
                                    <option value="<?= (int)$option['id'] ?>" <?= $selected ?>><?= $indent ? $indent . ' ' : '' ?><?= htmlspecialchars($option['title']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </label>
                        <label class="checkbox-field checkbox-inline">
                            <input type="checkbox" name="is_visible" value="1" <?= $visible ? 'checked' : '' ?>>
                            <span>anzeigen</span>
                        </label>
                        <button type="submit" class="button small">Speichern</button>
                    </form>
                </td>
                <td>
                    <a class="button secondary" href="<?= url('admin/pages', ['id' => $node['id']]) ?>">Bearbeiten</a>
                    <form method="post" action="<?= url('admin/pages') ?>" style="display:inline" onsubmit="return confirm('Diese Seite wirklich löschen?');">
                        <input type="hidden" name="id" value="<?= (int)$node['id'] ?>">
                        <input type="hidden" name="action" value="delete">
                        <button class="button danger" type="submit">Löschen</button>
                    </form>
                </td>
            </tr>
            <?php
            if (!empty($node['children'])) {
                renderPageTreeRows($node['children'], $parentOptions, $depth + 1);
            }
        }
    }
}

if (!function_exists('renderParentOptions')) {
    function renderParentOptions(array $options, ?int $selectedId, ?int $excludeId = null): void
    {
        ?>
        <option value="">(Keine)</option>
        <?php foreach ($options as $option): ?>
            <?php if ($excludeId !== null && (int)$option['id'] === $excludeId) { continue; } ?>
            <?php $indent = str_repeat('— ', (int)($option['depth'] ?? 0)); ?>
            <option value="<?= (int)$option['id'] ?>" <?= $selectedId === (int)$option['id'] ? 'selected' : '' ?>><?= $indent ? $indent . ' ' : '' ?><?= htmlspecialchars($option['title']) ?></option>
        <?php endforeach; ?>
        <?php
    }
}
?>

<section class="card">
    <h2>Menü &amp; Seitenstruktur</h2>
    <?php if (empty($pageTree)): ?>
        <p>Noch keine Seiten angelegt.</p>
    <?php else: ?>
        <table class="table">
            <thead>
                <tr>
                    <th>Seite</th>
                    <th>Slug</th>
                    <th>Menüstatus</th>
                    <th>Menüpflege</th>
                    <th>Aktionen</th>
                </tr>
            </thead>
            <tbody>
                <?php renderPageTreeRows($pageTree, $parentOptions); ?>
            </tbody>
        </table>
    <?php endif; ?>
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

        <div class="form-grid">
            <label>
                Übergeordnete Seite
                <select id="parent_id" name="parent_id">
                    <?php renderParentOptions($parentOptions, isset($editPage['parent_id']) ? (int)$editPage['parent_id'] : null, isset($editPage['id']) ? (int)$editPage['id'] : null); ?>
                </select>
            </label>
            <label>
                Reihenfolge
                <input id="menu_order" name="menu_order" type="number" value="<?= (int)($editPage['menu_order'] ?? $defaultMenuOrder) ?>">
            </label>
        </div>

        <label class="checkbox-field">
            <input type="checkbox" name="is_visible" value="1" <?= isset($editPage) ? ((int)$editPage['is_visible'] === 1 ? 'checked' : '') : 'checked' ?>>
            <span>Im Navigationsmenü anzeigen</span>
        </label>

        <button class="button" type="submit"><?= $editPage ? 'Speichern' : 'Erstellen' ?></button>
    </form>
</section>
