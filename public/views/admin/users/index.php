<section class="card">
    <h2>Benutzerverwaltung</h2>
    <div class="table-responsive">
        <table class="table">
            <thead>
            <tr>
                <th>Benutzername</th>
                <th>Rolle</th>
                <th>Berechtigungen</th>
                <th>Erstellt am</th>
                <th>Aktionen</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= htmlspecialchars($user['username']) ?></td>
                    <td><?= $user['role'] === 'admin' ? 'Administrator' : 'Editor' ?></td>
                    <td>
                        <?php if ($user['role'] === 'admin'): ?>
                            Vollzugriff
                        <?php elseif (!empty($user['permissions'])): ?>
                            <ul class="list-plain">
                                <?php foreach ($user['permissions'] as $key => $enabled): ?>
                                    <?php if (!$enabled) { continue; } ?>
                                    <li><?= htmlspecialchars($permissionLabels[$key] ?? $key) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            Keine Module freigeschaltet
                        <?php endif; ?>
                    </td>
                    <td><?= date('d.m.Y', strtotime($user['created_at'])) ?></td>
                    <td>
                        <a class="button secondary" href="<?= url('admin/users', ['id' => $user['id']]) ?>">Bearbeiten</a>
                        <?php if (!$activeUser || (int)$activeUser['id'] !== (int)$user['id']): ?>
                            <form method="post" action="<?= url('admin/users') ?>" style="display:inline" onsubmit="return confirm('Benutzer wirklich löschen?');">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?= (int)$user['id'] ?>">
                                <button class="button danger" type="submit">Löschen</button>
                            </form>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>

<section class="card">
    <h2><?= $editUser ? 'Benutzer bearbeiten' : 'Neuen Benutzer anlegen' ?></h2>
    <form method="post" action="<?= url('admin/users') ?>">
        <input type="hidden" name="action" value="save">
        <input type="hidden" name="id" value="<?= $editUser['id'] ?? '' ?>">

        <label for="username">Benutzername</label>
        <input id="username" name="username" type="text" value="<?= htmlspecialchars($editUser['username'] ?? '') ?>" required>

        <fieldset class="fieldset">
            <legend>Rolle</legend>
            <label class="checkbox-field checkbox-inline">
                <input type="radio" name="role" value="admin" <?= ($editUser['role'] ?? 'editor') === 'admin' ? 'checked' : '' ?>>
                <span>Administrator (alle Bereiche)</span>
            </label>
            <label class="checkbox-field checkbox-inline">
                <input type="radio" name="role" value="editor" <?= ($editUser['role'] ?? 'editor') === 'editor' ? 'checked' : '' ?>>
                <span>Editor (freigeschaltete Module)</span>
            </label>
        </fieldset>

        <div class="permissions-grid" data-permission-field>
            <span class="permissions-hint">Module für Editor freischalten:</span>
            <?php foreach ($permissionLabels as $key => $label): ?>
                <label class="checkbox-field">
                    <input type="checkbox" name="permissions[]" value="<?= htmlspecialchars($key) ?>" <?= !empty($editUser) && !empty($editUser['permissions'][$key]) ? 'checked' : '' ?>>
                    <span><?= htmlspecialchars($label) ?></span>
                </label>
            <?php endforeach; ?>
        </div>
        <p class="help-text">Berechtigungen werden nur angewendet, wenn die Rolle auf Editor steht.</p>

        <div class="form-grid">
            <label>
                Passwort
                <input name="password" type="password" <?= $editUser ? '' : 'required' ?>>
            </label>
            <label>
                Passwort bestätigen
                <input name="password_confirm" type="password" <?= $editUser ? '' : 'required' ?>>
            </label>
        </div>
        <?php if ($editUser): ?>
            <p class="help-text">Passwort nur ausfüllen, wenn es geändert werden soll.</p>
        <?php endif; ?>

        <button class="button" type="submit"><?= $editUser ? 'Speichern' : 'Benutzer anlegen' ?></button>
    </form>
</section>
