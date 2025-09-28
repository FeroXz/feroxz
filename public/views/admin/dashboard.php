<section class="card">
    <h2>Überblick</h2>
    <div class="grid">
        <div class="card">
            <h3>Beiträge</h3>
            <p><?= $postCount ?></p>
        </div>
        <div class="card">
            <h3>Seiten</h3>
            <p><?= $pageCount ?></p>
        </div>
        <div class="card">
            <h3>Galerie-Einträge</h3>
            <p><?= $galleryCount ?></p>
        </div>
        <div class="card">
            <h3>Genetik-Arten</h3>
            <p><?= $speciesCount ?></p>
        </div>
        <div class="card">
            <h3>Tiere</h3>
            <p><?= $animalCount ?></p>
        </div>
    </div>
</section>
<section class="card">
    <h3>Serverseitige Speicherung</h3>
    <p>Alle Inhalte werden persistent in <code>storage/cms.sqlite</code> abgelegt. Stelle sicher, dass das Verzeichnis für den Webserver beschreibbar ist.</p>
</section>
