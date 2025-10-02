
<?php include __DIR__ . '/../partials/header.php'; ?>
<section class="admin-shell">
<header class="admin-page-header">
    <div>
        <h1 class="admin-title">Admin-Dashboard</h1>
        <p class="admin-subtitle">Behalte jedes Detail deines digitalen Terrariums im Blick – von aktiven Bartagamen bis zu den neuesten Anfragen.</p>
    </div>
    <div class="admin-meta">
        <span class="badge">Pogona Pulse</span>
        <span><?= count($animals) ?> Tiere aktiv</span>
    </div>
</header>
<?php include __DIR__ . '/nav.php'; ?>
<div class="grid cards">
    <div class="card">
        <h3>Aktive Tiere</h3>
        <p><?= count($animals) ?> Datensätze</p>
    </div>
    <div class="card">
        <h3>Abgabe-Einträge</h3>
        <p><?= count($listings) ?> Inserate</p>
    </div>
    <div class="card">
        <h3>Neue Anfragen</h3>
        <p><?= count($inquiries) ?> Nachrichten</p>
    </div>
    <div class="card">
        <h3>Seiten</h3>
        <p><?= count($pages) ?> Einträge</p>
    </div>
    <div class="card">
        <h3>News</h3>
        <p><?= count($newsPosts) ?> Beiträge</p>
    </div>
    <div class="card">
        <h3>Zuchtpläne</h3>
        <p><?= count($breedingPlans) ?> Projekte</p>
    </div>
    <div class="card">
        <h3>Pflegeartikel</h3>
        <p><?= count($careArticles) ?> Artikel</p>
    </div>
    <div class="card">
        <h3>Genetische Arten</h3>
        <p><?= isset($geneticSpecies) ? count($geneticSpecies) : 0 ?> Datensätze</p>
    </div>
    <div class="card">
        <h3>Gene</h3>
        <p><?= isset($geneticGenes) ? count($geneticGenes) : 0 ?> Einträge</p>
    </div>
</div>
<section class="admin-section">
    <h2>Letzte Anfragen</h2>
    <div class="card">
        <?php if (empty($inquiries)): ?>
            Keine Anfragen vorhanden.
        <?php else: ?>
            <div class="table-responsive">
                <table class="table">
                <thead>
                    <tr>
                        <th>Datum</th>
                        <th>Tier</th>
                        <th>Name</th>
                        <th>E-Mail</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach (array_slice($inquiries, 0, 5) as $inquiry): ?>
                        <tr>
                            <td><?= htmlspecialchars($inquiry['created_at']) ?></td>
                            <td><?= htmlspecialchars($inquiry['listing_title']) ?></td>
                            <td><?= htmlspecialchars($inquiry['sender_name']) ?></td>
                            <td><a href="mailto:<?= htmlspecialchars($inquiry['sender_email']) ?>">Kontakt aufnehmen</a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</section>
</section>
<?php include __DIR__ . '/../partials/footer.php'; ?>

