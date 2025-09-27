<section class="genetics species-detail">
  <header class="page-header">
    <h1><?= htmlspecialchars($species['name'], ENT_QUOTES, 'UTF-8') ?></h1>
    <?php if (!empty($species['scientific_name'])): ?>
    <p class="scientific"><?= htmlspecialchars($species['scientific_name'], ENT_QUOTES, 'UTF-8') ?></p>
    <?php endif; ?>
    <div class="actions">
      <a class="button" href="/genetics/<?= htmlspecialchars($species['slug'], ENT_QUOTES, 'UTF-8') ?>/calculator">Zum Genetik-Rechner</a>
      <a class="button secondary" href="/genetics">Zurück zur Übersicht</a>
    </div>
  </header>

  <?php if (!empty($species['description'])): ?>
  <p><?= nl2br(htmlspecialchars($species['description'], ENT_QUOTES, 'UTF-8')) ?></p>
  <?php endif; ?>

  <h2>Gene &amp; Morphe</h2>
  <?php if (empty($genes)): ?>
  <p class="empty">Für diese Art wurden noch keine Gene erfasst.</p>
  <?php else: ?>
  <div class="gene-list">
    <?php foreach ($genes as $gene): ?>
    <article class="card">
      <h3><?= htmlspecialchars($gene['name'], ENT_QUOTES, 'UTF-8') ?></h3>
      <dl>
        <div>
          <dt>Vererbung</dt>
          <dd><?= htmlspecialchars($inheritanceLabels[$gene['inheritance_type']] ?? $gene['inheritance_type'], ENT_QUOTES, 'UTF-8') ?></dd>
        </div>
        <div>
          <dt>0 Kopien</dt>
          <dd><?= htmlspecialchars($gene['normal_label'], ENT_QUOTES, 'UTF-8') ?></dd>
        </div>
        <div>
          <dt>1 Kopie</dt>
          <dd><?= htmlspecialchars($gene['heterozygous_label'], ENT_QUOTES, 'UTF-8') ?></dd>
        </div>
        <div>
          <dt>2 Kopien</dt>
          <dd><?= htmlspecialchars($gene['homozygous_label'], ENT_QUOTES, 'UTF-8') ?></dd>
        </div>
      </dl>
      <?php if (!empty($gene['description'])): ?>
      <p><?= nl2br(htmlspecialchars($gene['description'], ENT_QUOTES, 'UTF-8')) ?></p>
      <?php endif; ?>
    </article>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>
</section>
