<?php include __DIR__ . '/../partials/header.php'; ?>
<section class="section">
    <div class="section__inner" style="max-width:480px;">
        <article class="card">
            <h1 class="card__title">404 – Seite nicht gefunden</h1>
            <p class="card__subtitle">Die angeforderte Seite existiert nicht oder wurde verschoben. Bitte prüfen Sie die URL oder kehren Sie zur Startseite zurück.</p>
            <div class="form-actions">
                <a class="button button--outline" href="<?= BASE_URL ?>/index.php">Zur Startseite</a>
            </div>
        </article>
    </div>
</section>
<?php include __DIR__ . '/../partials/footer.php'; ?>
