<section class="genetics">
  <h1>Genetik-Datenbank</h1>
  <p>
    Entdecke dokumentierte Gene und Morphe für unsere unterstützten Arten und starte
    den Genetik-Rechner, um mögliche Nachzuchten zu simulieren.
  </p>

  <?php if (empty($species)): ?>
  <p class="empty">Es wurden noch keine Arten hinterlegt.</p>
  <?php else: ?>
  <div class="grid">
    <?php foreach ($species as $entry): ?>
    <article class="card">
      <h2>
        <a href="/genetics/<?= htmlspecialchars($entry['slug'], ENT_QUOTES, 'UTF-8') ?>">
          <?= htmlspecialchars($entry['name'], ENT_QUOTES, 'UTF-8') ?>
        </a>
      </h2>
      <?php if (!empty($entry['scientific_name'])): ?>
      <p class="scientific"><?= htmlspecialchars($entry['scientific_name'], ENT_QUOTES, 'UTF-8') ?></p>
      <?php endif; ?>
      <?php if (!empty($entry['description'])): ?>
      <p><?= nl2br(htmlspecialchars($entry['description'], ENT_QUOTES, 'UTF-8')) ?></p>
      <?php endif; ?>
      <div class="actions">
        <a class="button" href="/genetics/<?= htmlspecialchars($entry['slug'], ENT_QUOTES, 'UTF-8') ?>">Gene anzeigen</a>
        <a class="button secondary" href="/genetics/<?= htmlspecialchars($entry['slug'], ENT_QUOTES, 'UTF-8') ?>/calculator">Genetik-Rechner</a>
      </div>
    </article>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>
</section>
