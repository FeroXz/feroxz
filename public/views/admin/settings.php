<?php include __DIR__ . '/../partials/header.php'; ?>
<section class="admin-shell">
<header class="admin-page-header">
    <div>
        <h1 class="admin-title">Einstellungen</h1>
        <p class="admin-subtitle">Forme die digitale Umgebung – von Terrarium-Branding bis Kontakt – mit wenigen, fokussierten Eingaben.</p>
    </div>
    <div class="admin-meta">
        <span class="badge">Systemkern</span>
        <span>Individuelle Konfiguration</span>
    </div>
</header>
<?php include __DIR__ . '/nav.php'; ?>
<div class="admin-section">
<?php if ($flashSuccess): ?>
    <div class="alert alert-success" role="status" aria-live="polite"><?= htmlspecialchars($flashSuccess) ?></div>
<?php endif; ?>
<div class="card">
    <form method="post">
        <label>Seitentitel
            <input type="text" name="site_title" value="<?= htmlspecialchars($settings['site_title'] ?? '') ?>">
        </label>
        <label>Untertitel
            <input type="text" name="site_tagline" value="<?= htmlspecialchars($settings['site_tagline'] ?? '') ?>">
        </label>
        <label>Hero-Einleitung
            <textarea name="hero_intro" class="rich-text"><?= htmlspecialchars($settings['hero_intro'] ?? '') ?></textarea>
        </label>
        <label>Abgabe Intro
            <textarea name="adoption_intro" class="rich-text"><?= htmlspecialchars($settings['adoption_intro'] ?? '') ?></textarea>
        </label>
        <label>Footer Text
            <textarea name="footer_text" class="rich-text"><?= htmlspecialchars($settings['footer_text'] ?? '') ?></textarea>
        </label>
        <label>Kontakt E-Mail
            <input type="email" name="contact_email" value="<?= htmlspecialchars($settings['contact_email'] ?? '') ?>">
        </label>
        <button type="submit">Speichern</button>
    </form>
</div>
</div>
</section>
<?php include __DIR__ . '/../partials/footer.php'; ?>
