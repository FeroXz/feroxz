<?php include __DIR__ . '/../partials/header.php'; ?>
<h1>Admin-Dashboard</h1>
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
        <h3>Seiten & Beiträge</h3>
        <p><?= count($pages) ?> Seiten • <?= count($posts) ?> Beiträge</p>
    </div>
    <div class="card">
        <h3>Galerie</h3>
        <p><?= count($gallery) ?> Medien</p>
    </div>
</div>

<section style="margin-top:2rem;">
    <h2>Letzte Anfragen</h2>
    <div class="card">
        <?php if (empty($inquiries)): ?>
            Keine Anfragen vorhanden.
        <?php else: ?>
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
        <?php endif; ?>
    </div>
</section>

<section style="margin-top:2rem;">
    <h2>Journal-Aktivität</h2>
    <div class="card">
        <?php if (empty($posts)): ?>
            Noch keine Beiträge veröffentlicht.
        <?php else: ?>
            <ul>
                <?php foreach (array_slice($posts, 0, 5) as $post): ?>
                    <li><?= htmlspecialchars($post['title']) ?><?= !empty($post['published_at']) ? ' – ' . date('d.m.Y', strtotime($post['published_at'])) : '' ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>
</section>
<?php include __DIR__ . '/../partials/footer.php'; ?>
