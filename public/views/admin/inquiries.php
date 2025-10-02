<?php include __DIR__ . '/../partials/header.php'; ?>
<section class="admin-shell">
<header class="admin-page-header">
    <div>
        <h1 class="admin-title">Anfragen</h1>
        <p class="admin-subtitle">Reagiere auf jede Stimme aus dem Terrarium – fokussiert, schnell und in einem ruhigen Interface.</p>
    </div>
    <div class="admin-meta">
        <span class="badge">Kontaktstrom</span>
        <span><?= count($inquiries) ?> Nachrichten</span>
    </div>
</header>
<?php include __DIR__ . '/nav.php'; ?>
<div class="admin-section">
    <div class="card">
        <?php if (empty($inquiries)): ?>
            Keine Nachrichten vorhanden.
        <?php else: ?>
        <div class="table-responsive">
            <table class="table">
            <thead>
                <tr>
                    <th>Datum</th>
                    <th>Inserat</th>
                    <th>Name</th>
                    <th>E-Mail</th>
                    <th>Nachricht</th>
                    <th>Antwort</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($inquiries as $inquiry): ?>
                    <tr>
                        <td><?= htmlspecialchars($inquiry['created_at']) ?></td>
                        <td><?= htmlspecialchars($inquiry['listing_title']) ?></td>
                        <td><?= htmlspecialchars($inquiry['sender_name']) ?></td>
                        <td><a href="mailto:<?= htmlspecialchars($inquiry['sender_email']) ?>">Mail</a></td>
                        <td><?= nl2br(htmlspecialchars($inquiry['message'])) ?></td>
                        <td><a class="btn btn-secondary" href="mailto:<?= htmlspecialchars($inquiry['sender_email']) ?>?subject=Re:%20<?= urlencode($inquiry['listing_title']) ?>">Antworten</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>
</div>
</section>
<?php include __DIR__ . '/../partials/footer.php'; ?>
