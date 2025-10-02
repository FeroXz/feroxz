<section class="gallery">
  <header class="hero">
    <div class="hero-text">
      <h1>Galerie</h1>
      <p>Entdecke hochgeladene Medien und Inhalte.</p>
    </div>
  </header>

  <?php if (!empty($items)): ?>
  <div class="gallery-grid">
    <?php foreach ($items as $item): ?>
    <figure class="gallery-item">

      <a
        href="<?= htmlspecialchars(asset('static/uploads/' . $item['filename']), ENT_QUOTES, 'UTF-8') ?>"
        target="_blank"
      >
        <img
          src="<?= htmlspecialchars(asset('static/uploads/' . $item['filename']), ENT_QUOTES, 'UTF-8') ?>"
          alt="<?= htmlspecialchars($item['title'], ENT_QUOTES, 'UTF-8') ?>"
        />

      </a>
      <figcaption>
        <strong><?= htmlspecialchars($item['title'], ENT_QUOTES, 'UTF-8') ?></strong>
        <?php if (!empty($item['description'])): ?>
        <p><?= htmlspecialchars($item['description'], ENT_QUOTES, 'UTF-8') ?></p>
        <?php endif; ?>
      </figcaption>
    </figure>
    <?php endforeach; ?>
  </div>
  <?php else: ?>
  <div class="empty-state">
    <h2>Noch keine Galerie-Elemente</h2>
    <p>Sobald Medien hochgeladen wurden, erscheinen sie hier.</p>
  </div>
  <?php endif; ?>
</section>
