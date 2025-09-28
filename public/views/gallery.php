<section class="card">
    <h2>Galerie</h2>
    <p>Uploads werden im Webspace gespeichert. Du kannst Einträge im Adminbereich ergänzen oder bearbeiten.</p>
    <div class="gallery-grid">
        <?php foreach ($items as $item): ?>
            <div class="gallery-item">
                <?php if ($item['image_path']): ?>
                    <img src="<?= htmlspecialchars($item['image_path']) ?>" alt="<?= htmlspecialchars($item['title']) ?>">
                <?php endif; ?>
                <div class="content">
                    <h3><?= htmlspecialchars($item['title']) ?></h3>
                    <p><?= nl2br(htmlspecialchars($item['description'])) ?></p>
                    <small>Erstellt am <?= date('d.m.Y', strtotime($item['created_at'])) ?></small>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>
