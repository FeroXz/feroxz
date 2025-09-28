<article class="card">
    <h2><?= htmlspecialchars($post['title']) ?></h2>
    <p class="meta">Veröffentlicht am <?= date('d.m.Y', strtotime($post['published_at'])) ?></p>
    <div class="content"><?= nl2br(htmlspecialchars($post['content'])) ?></div>
    <p><a href="<?= url('home') ?>">Zurück zur Übersicht</a></p>
</article>
