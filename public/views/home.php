<section class="hero">
  <div class="hero-text">
    <h1>Willkommen beim Feroxz CMS</h1>
    <p>
      Verwalte Inhalte in Sekundenschnelle mit einem einfachen, modernen und
      mobilen Backend.
    </p>

    <a class="btn" href="<?= htmlspecialchars(path('/admin/login'), ENT_QUOTES, 'UTF-8') ?>">Zum Adminbereich</a>

  </div>
</section>
<section class="posts-grid">
  <?php if (!empty($posts)): ?>
  <?php foreach ($posts as $post): ?>
  <article class="card">
    <div class="card-header">
      <h2><?= htmlspecialchars($post['title'], ENT_QUOTES, 'UTF-8') ?></h2>
      <span class="timestamp">Aktualisiert: <?= htmlspecialchars($post['updated_at'], ENT_QUOTES, 'UTF-8') ?></span>
    </div>
    <p><?= nl2br(htmlspecialchars($post['content'], ENT_QUOTES, 'UTF-8')) ?></p>
  </article>
  <?php endforeach; ?>
  <?php else: ?>
  <div class="empty-state">
    <h2>Noch keine Beiträge vorhanden</h2>
    <p>Logge dich ein, um den ersten Beitrag zu erstellen.</p>
  </div>
  <?php endif; ?>
</section>
