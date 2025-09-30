<?php $title = 'Einstellungen'; ?>
<section class="panel">
    <header class="panel__header">
        <div>
            <h1>Seitentexte &amp; Branding</h1>
            <p>Passe Titel, Slogan, Footertext und Texte für die Start- und Tierabgabeseite an.</p>
        </div>
    </header>
    <form action="<?= url('admin/settings') ?>" method="post" class="form-grid">
        <div class="form-grid__row">
            <label>
                <span>Seitentitel *</span>
                <input type="text" name="site_title" value="<?= htmlspecialchars(settingValue($settings, 'site_title', 'Feroxz')) ?>" required>
            </label>
            <label>
                <span>Tagline</span>
                <input type="text" name="site_tagline" value="<?= htmlspecialchars(settingValue($settings, 'site_tagline', 'Reptilienverwaltung & Genetik')) ?>">
            </label>
            <label>
                <span>Kontakt E-Mail</span>
                <input type="email" name="contact_email" value="<?= htmlspecialchars(settingValue($settings, 'contact_email', '')) ?>" placeholder="info@example.com">
            </label>
        </div>
        <label>
            <span>Footertext</span>
            <textarea name="footer_text" rows="3" placeholder="Erscheint im Fußbereich jeder Seite."><?= htmlspecialchars(settingValue($settings, 'footer_text', '')) ?></textarea>
        </label>
        <div class="form-grid__row">
            <label>
                <span>Startseiten-Headline</span>
                <input type="text" name="home_hero_title" value="<?= htmlspecialchars(settingValue($settings, 'home_hero_title', 'Reptilien nachhaltig züchten & vermitteln')) ?>">
            </label>
            <label>
                <span>Startseiten-Untertitel</span>
                <input type="text" name="home_hero_subtitle" value="<?= htmlspecialchars(settingValue($settings, 'home_hero_subtitle', 'Pflegeleitfäden, Genetik-Tools und ein moderner Tierbestand – alles in einem CMS.')) ?>">
            </label>
        </div>
        <label>
            <span>Text für die Tierabgabe</span>
            <textarea name="adoption_intro" rows="4" placeholder="Einleitungstext, der Besuchern den Ablauf erklärt."><?= htmlspecialchars(settingValue($settings, 'adoption_intro', 'Hier findest du Reptilien aus verantwortungsvoller Haltung, die ein neues Zuhause suchen.')) ?></textarea>
        </label>
        <div class="form-actions">
            <button type="submit" class="button">Speichern</button>
        </div>
    </form>
</section>
