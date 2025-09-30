<?php
$title = 'Tierabgabe verwalten';
$statusLabels = adoptionStatusOptions();
$inquiryLabels = adoptionInquiryStatusOptions();
$edit = $editAdoption ?? null;
?>
<section class="admin-grid">
    <div class="panel">
        <header class="panel__header">
            <div>
                <h1><?= $edit ? 'Tierabgabe bearbeiten' : 'Tier zur Abgabe einstellen' ?></h1>
                <p>Pflege alle Informationen inklusive Genetik, Preis und Status. Bilder werden automatisch optimiert.</p>
            </div>
            <?php if ($edit): ?>
                <a class="button subtle" href="<?= url('admin/adoptions') ?>">Neu anlegen</a>
            <?php endif; ?>
        </header>
        <form action="<?= url('admin/adoptions') ?>" method="post" enctype="multipart/form-data" class="form-grid">
            <input type="hidden" name="action" value="save">
            <input type="hidden" name="id" value="<?= $edit ? (int)$edit['id'] : 0 ?>">
            <label>
                <span>Name *</span>
                <input type="text" name="name" value="<?= htmlspecialchars($edit['name'] ?? '') ?>" required>
            </label>
            <label>
                <span>Art *</span>
                <input type="text" name="species" value="<?= htmlspecialchars($edit['species'] ?? '') ?>" required>
            </label>
            <label>
                <span>Genetik</span>
                <textarea name="genetics" rows="3" placeholder="Albino het Hypo …"><?= htmlspecialchars($edit['genetics'] ?? '') ?></textarea>
            </label>
            <label>
                <span>Beschreibung</span>
                <textarea name="description" rows="4" placeholder="Temperament, Besonderheiten, Haltungshinweise …"><?= htmlspecialchars($edit['description'] ?? '') ?></textarea>
            </label>
            <div class="form-grid__row">
                <label>
                    <span>Geburtsdatum / Alter</span>
                    <input type="text" name="birth_date" value="<?= htmlspecialchars($edit['birth_date'] ?? '') ?>" placeholder="z. B. März 2023">
                </label>
                <label>
                    <span>Preis</span>
                    <input type="text" name="price" value="<?= htmlspecialchars($edit['price'] ?? '') ?>" placeholder="z. B. 350 €">
                </label>
                <label>
                    <span>Status</span>
                    <select name="status">
                        <?php foreach ($statusLabels as $value => $label): ?>
                            <option value="<?= htmlspecialchars($value) ?>" <?= ($edit['status'] ?? 'available') === $value ? 'selected' : '' ?>><?= htmlspecialchars($label) ?></option>
                        <?php endforeach; ?>
                    </select>
                </label>
                <label>
                    <span>Sortierung</span>
                    <input type="number" name="sort_order" value="<?= htmlspecialchars($edit['sort_order'] ?? 0) ?>" min="0">
                </label>
            </div>
            <div class="form-grid__row">
                <label class="file-input">
                    <span>Bild</span>
                    <input type="file" name="image" accept="image/*">
                </label>
                <?php if (!empty($edit['image_path'])): ?>
                    <div class="form-preview">
                        <img src="<?= htmlspecialchars($edit['image_path']) ?>" alt="<?= htmlspecialchars($edit['name']) ?>">
                        <label class="checkbox-inline">
                            <input type="checkbox" name="remove_image" value="1">
                            <span>Aktuelles Bild entfernen</span>
                        </label>
                    </div>
                <?php endif; ?>
            </div>
            <div class="form-actions">
                <button type="submit" class="button">Speichern</button>
            </div>
        </form>
    </div>
    <div class="panel">
        <header class="panel__header">
            <div>
                <h2>Verfügbare Tiere</h2>
                <p>Verwalte Status, Preise und Reihenfolge der veröffentlichten Tiere.</p>
            </div>
        </header>
        <?php if (empty($adoptions)): ?>
            <p>Noch keine Tiere angelegt.</p>
        <?php else: ?>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Art</th>
                        <th>Status</th>
                        <th>Preis</th>
                        <th>Aktualisiert</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($adoptions as $animal): ?>
                        <tr>
                            <td><?= htmlspecialchars($animal['name']) ?></td>
                            <td><?= htmlspecialchars($animal['species']) ?></td>
                            <td><span class="badge badge--<?= htmlspecialchars($animal['status']) ?>"><?= htmlspecialchars($statusLabels[$animal['status']] ?? $animal['status']) ?></span></td>
                            <td><?= htmlspecialchars($animal['price'] ?? '') ?></td>
                            <?php $updatedAt = $animal['updated_at'] ?: ($animal['created_at'] ?? date(DATE_ATOM)); ?>
                            <td><?= htmlspecialchars(date('d.m.Y', strtotime($updatedAt))) ?></td>
                            <td class="table-actions">
                                <a class="button subtle" href="<?= url('admin/adoptions', ['id' => (int)$animal['id']]) ?>">Bearbeiten</a>
                                <form method="post" action="<?= url('admin/adoptions') ?>" onsubmit="return confirm('Tier wirklich löschen?');">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?= (int)$animal['id'] ?>">
                                    <button type="submit" class="button danger">Löschen</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</section>

<section class="panel">
    <header class="panel__header">
        <div>
            <h2>Anfragen</h2>
            <p>Beantworte Interessenten direkt aus dem Adminbereich. Der Reply-Link öffnet dein Standard-Mailprogramm.</p>
        </div>
    </header>
    <?php if (empty($inquiries)): ?>
        <p>Es liegen noch keine Anfragen vor.</p>
    <?php else: ?>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Datum</th>
                    <th>Tier</th>
                    <th>Interessent</th>
                    <th>Status</th>
                    <th>Nachricht</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($inquiries as $inquiry): ?>
                    <tr>
                        <td><?= htmlspecialchars(date('d.m.Y H:i', strtotime($inquiry['created_at']))) ?></td>
                        <td><?= htmlspecialchars($inquiry['adoption_name']) ?></td>
                        <td>
                            <strong><?= htmlspecialchars($inquiry['interested_name']) ?></strong><br>
                            <a href="mailto:<?= htmlspecialchars($inquiry['interested_email']) ?>?subject=Rückmeldung%20zu%20<?= urlencode($inquiry['adoption_name']) ?>"><?= htmlspecialchars($inquiry['interested_email']) ?></a>
                        </td>
                        <td>
                            <form method="post" action="<?= url('admin/adoptions') ?>" class="inline-form">
                                <input type="hidden" name="action" value="inquiry-status">
                                <input type="hidden" name="inquiry_id" value="<?= (int)$inquiry['id'] ?>">
                                <select name="status">
                                    <?php foreach ($inquiryLabels as $value => $label): ?>
                                        <option value="<?= htmlspecialchars($value) ?>" <?= $inquiry['status'] === $value ? 'selected' : '' ?>><?= htmlspecialchars($label) ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <button type="submit" class="button subtle">Aktualisieren</button>
                            </form>
                        </td>
                        <td>
                            <?php if (!empty($inquiry['message'])): ?>
                                <details>
                                    <summary>Details anzeigen</summary>
                                    <pre><?= htmlspecialchars($inquiry['message']) ?></pre>
                                </details>
                            <?php endif; ?>
                        </td>
                        <td class="table-actions">
                            <a class="button" href="mailto:<?= htmlspecialchars($inquiry['interested_email']) ?>?subject=Rückmeldung%20zu%20<?= urlencode($inquiry['adoption_name']) ?>">Antworten</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</section>
