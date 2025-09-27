<section class="admin genetics-overview">
  <h1>Genetikverwaltung</h1>
  <p>Verwalte die verfügbaren Arten und ihre Gene für den Genetik-Rechner.</p>

  <?php if (empty($species)): ?>
  <p class="empty">Es sind keine Arten hinterlegt.</p>
  <?php else: ?>
  <table class="table">
    <thead>
      <tr>
        <th>Art</th>
        <th>Wissenschaftlicher Name</th>
        <th>Gene</th>
        <th>Aktionen</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($species as $entry): ?>
      <tr>
        <td><?= htmlspecialchars($entry['name'], ENT_QUOTES, 'UTF-8') ?></td>
        <td><?= htmlspecialchars($entry['scientific_name'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
        <td><?= (int) ($entry['gene_count'] ?? 0) ?></td>
        <td>

          <a
            class="button"
            href="<?= htmlspecialchars(path('/admin/genetics/species/' . $entry['slug']), ENT_QUOTES, 'UTF-8') ?>"
          >Gene verwalten</a>
          <a
            class="button secondary"
            href="<?= htmlspecialchars(path('/genetics/' . $entry['slug']), ENT_QUOTES, 'UTF-8') ?>"
            target="_blank"
          >Öffnen</a>

        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <?php endif; ?>
</section>
