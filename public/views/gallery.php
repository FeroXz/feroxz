<?php $title = 'Galerie'; ?>
<section class="panel">
    <header class="panel__header">
        <div>
            <h2>Galerie</h2>
            <p>Uploads werden direkt auf dem Webspace gespeichert. Ergänze neue Einträge bequem im Adminbereich.</p>
        </div>
    </header>
    <div class="gallery-grid">
        <?php foreach ($items as $item): ?>
            <article class="gallery-card">
                <?php if ($item['image_path']): ?>
                    <img src="<?= htmlspecialchars($item['image_path']) ?>" alt="<?= htmlspecialchars($item['title']) ?>">
                <?php endif; ?>
                <div class="gallery-card__body">
                    <h3><?= htmlspecialchars($item['title']) ?></h3>
                    <p><?= nl2br(htmlspecialchars($item['description'])) ?></p>
                    <small>Erstellt am <?= date('d.m.Y', strtotime($item['created_at'])) ?></small>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
</section>
