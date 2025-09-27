<section class="admin genetics-species">
  <header class="page-header">
    <h1>Gene verwalten – <?= htmlspecialchars($species['name'], ENT_QUOTES, 'UTF-8') ?></h1>
    <div class="actions">
      <a class="button" href="/admin/genetics/species/<?= htmlspecialchars($species['slug'], ENT_QUOTES, 'UTF-8') ?>/genes/new">Neues Gen</a>
      <a class="button secondary" href="/admin/genetics">Zurück</a>
    </div>
  </header>

  <?php if (empty($genes)): ?>
  <p class="empty">Es sind noch keine Gene für diese Art hinterlegt.</p>
  <?php else: ?>
  <table class="table">
    <thead>
      <tr>
        <th>Name</th>
        <th>Vererbung</th>
        <th>0 Kopien</th>
        <th>1 Kopie</th>
        <th>2 Kopien</th>
        <th>Aktionen</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($genes as $gene): ?>
      <tr>
        <td><?= htmlspecialchars($gene['name'], ENT_QUOTES, 'UTF-8') ?></td>
        <td><?= htmlspecialchars($inheritanceLabels[$gene['inheritance_type']] ?? $gene['inheritance_type'], ENT_QUOTES, 'UTF-8') ?></td>
        <td><?= htmlspecialchars($gene['normal_label'], ENT_QUOTES, 'UTF-8') ?></td>
        <td><?= htmlspecialchars($gene['heterozygous_label'], ENT_QUOTES, 'UTF-8') ?></td>
        <td><?= htmlspecialchars($gene['homozygous_label'], ENT_QUOTES, 'UTF-8') ?></td>
        <td class="actions">
          <a class="button" href="/admin/genetics/species/<?= htmlspecialchars($species['slug'], ENT_QUOTES, 'UTF-8') ?>/genes/<?= (int) $gene['id'] ?>/edit">Bearbeiten</a>
          <form method="post" action="/admin/genetics/species/<?= htmlspecialchars($species['slug'], ENT_QUOTES, 'UTF-8') ?>/genes/<?= (int) $gene['id'] ?>/delete" onsubmit="return confirm('Dieses Gen wirklich löschen?');">
            <button type="submit" class="button danger">Löschen</button>
          </form>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <?php endif; ?>
</section>
