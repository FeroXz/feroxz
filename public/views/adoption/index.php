<?php
$title = 'Tierabgabe';
$statusLabels = adoptionStatusOptions();
?>
<section class="page-hero adoption-hero">
    <div class="page-hero__content">
        <span class="eyebrow">Tiervermittlung</span>
        <h1>Tierabgabe</h1>
        <p><?= nl2br(htmlspecialchars(settingValue($settings, 'adoption_intro', 'Finde verantwortungsvoll gezogene Reptilien, die ein neues Zuhause suchen.'))) ?></p>
    </div>
</section>

<?php $contactEmail = settingValue($settings, 'contact_email', ''); ?>
<?php if (filter_var($contactEmail, FILTER_VALIDATE_EMAIL)): ?>
    <section class="panel info-callout">
        <div class="info-callout__content">
            <h2>Direkter Kontakt</h2>
            <p>Für Rückfragen oder individuelle Absprachen erreichst du uns unter <a href="mailto:<?= htmlspecialchars($contactEmail) ?>"><?= htmlspecialchars($contactEmail) ?></a>. Wir melden uns in der Regel binnen 24&nbsp;Stunden.</p>
        </div>
        <div class="info-callout__meta">
            <span>Antwortzeit</span>
            <strong>0–24&nbsp;h</strong>
        </div>
    </section>
<?php endif; ?>

<?php if (empty($adoptions)): ?>
    <section class="panel">
        <h2>Aktuell keine Tiere verfügbar</h2>
        <p>Alle Tiere sind derzeit vermittelt oder reserviert. Schau bald wieder vorbei oder kontaktiere uns direkt für zukünftige Projekte.</p>
    </section>
<?php else: ?>
    <section class="adoption-grid">
        <?php foreach ($adoptions as $animal): ?>
            <?php
            $status = $animal['status'] ?? 'available';
            $statusLabel = $statusLabels[$status] ?? ucfirst($status);
            $canInquire = in_array($status, ['available', 'reserved'], true);
            ?>
            <article class="adoption-card">
                <div class="adoption-card__media">
                    <?php if (!empty($animal['image_path'])): ?>
                        <img src="<?= htmlspecialchars($animal['image_path']) ?>" alt="<?= htmlspecialchars($animal['name']) ?>">
                    <?php else: ?>
                        <div class="adoption-card__placeholder" aria-hidden="true">🐍</div>
                    <?php endif; ?>
                </div>
                <div class="adoption-card__body">
                    <header class="adoption-card__header">
                        <div>
                            <h2><?= htmlspecialchars($animal['name']) ?></h2>
                            <p class="adoption-card__subtitle">Art: <?= htmlspecialchars($animal['species']) ?></p>
                        </div>
                        <span class="badge badge--<?= htmlspecialchars($status) ?>"><?= htmlspecialchars($statusLabel) ?></span>
                    </header>
                    <div class="adoption-card__meta">
                        <?php if (!empty($animal['genetics'])): ?>
                            <div>
                                <span>Genetik</span>
                                <strong><?= nl2br(htmlspecialchars($animal['genetics'])) ?></strong>
                            </div>
                        <?php endif; ?>
                        <?php if (!empty($animal['birth_date'])): ?>
                            <div>
                                <span>Geboren</span>
                                <strong><?= htmlspecialchars($animal['birth_date']) ?></strong>
                            </div>
                        <?php endif; ?>
                        <?php if (!empty($animal['price'])): ?>
                            <div>
                                <span>Preis</span>
                                <strong><?= htmlspecialchars($animal['price']) ?></strong>
                            </div>
                        <?php endif; ?>
                    </div>
                    <?php if (!empty($animal['description'])): ?>
                        <p class="adoption-card__description"><?= nl2br(htmlspecialchars($animal['description'])) ?></p>
                    <?php endif; ?>
                    <?php if ($canInquire): ?>
                        <form class="adoption-form" method="post" action="<?= url('adoption/inquire') ?>">
                            <h3>Interesse? Jetzt anfragen</h3>
                            <input type="hidden" name="adoption_id" value="<?= (int)$animal['id'] ?>">
                            <input type="hidden" name="adoption_name" value="<?= htmlspecialchars($animal['name']) ?>">
                            <div class="form-grid">
                                <label>
                                    <span>Name *</span>
                                    <input type="text" name="name" required>
                                </label>
                                <label>
                                    <span>E-Mail *</span>
                                    <input type="email" name="email" required>
                                </label>
                                <label>
                                    <span>Telefon</span>
                                    <input type="text" name="phone" placeholder="optional">
                                </label>
                            </div>
                            <label>
                                <span>Nachricht</span>
                                <textarea name="message" rows="3" placeholder="Erzähle kurz, warum dich <?= htmlspecialchars($animal['name']) ?> interessiert."></textarea>
                            </label>
                            <label>
                                <span>Weitere Infos</span>
                                <textarea name="details" rows="3" placeholder="Haltungserfahrung, Standort, verfügbare Terrarien …"></textarea>
                            </label>
                            <button type="submit" class="button">Anfrage senden</button>
                        </form>
                    <?php else: ?>
                        <p class="adoption-card__note">Dieses Tier ist bereits vermittelt. Du kannst uns trotzdem kontaktieren, um dich für zukünftige Nachzuchten vormerken zu lassen.</p>
                    <?php endif; ?>
                </div>
            </article>
        <?php endforeach; ?>
    </section>
<?php endif; ?>
