<?php $title = 'Dashboard'; ?>
<section class="panel">
    <header class="panel__header">
        <div>
            <h2>Überblick</h2>
            <p>Aktuelle Kennzahlen aus Content-, Tier- und Tierabgabeverwaltung.</p>
        </div>
    </header>
    <div class="card-grid stats-grid">
        <article class="info-card">
            <h3>Beiträge</h3>
            <p class="stat-number"><?= $postCount ?></p>
        </article>
        <article class="info-card">
            <h3>Seiten</h3>
            <p class="stat-number"><?= $pageCount ?></p>
        </article>
        <article class="info-card">
            <h3>Galerie-Einträge</h3>
            <p class="stat-number"><?= $galleryCount ?></p>
        </article>
        <article class="info-card">
            <h3>Genetik-Arten</h3>
            <p class="stat-number"><?= $speciesCount ?></p>
        </article>
        <article class="info-card">
            <h3>Tiere</h3>
            <p class="stat-number"><?= $animalCount ?></p>
        </article>
        <article class="info-card">
            <h3>Tierabgabe</h3>
            <p class="stat-number"><?= $adoptionCount ?></p>
        </article>
        <article class="info-card">
            <h3>Anfragen</h3>
            <p class="stat-number"><?= $inquiryCount ?></p>
        </article>
    </div>
</section>
<section class="panel">
    <h3>Serverseitige Speicherung</h3>
    <p>Alle Inhalte werden persistent in <code>storage/cms.sqlite</code> abgelegt. Stelle sicher, dass das Verzeichnis für den Webserver beschreibbar ist.</p>
</section>
