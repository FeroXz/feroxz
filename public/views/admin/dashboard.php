<section class="dashboard">
  <div class="dashboard-header">
    <h1>Inhalte verwalten</h1>
  </div>

  <div class="dashboard-section">
    <div class="dashboard-section-header">
      <h2>Beiträge</h2>

      <a class="btn" href="<?= htmlspecialchars(path('/admin/posts/new'), ENT_QUOTES, 'UTF-8') ?>">Neuer Beitrag</a>

    </div>
    <?php if (!empty($posts)): ?>
    <table class="table">
      <thead>
        <tr>
          <th>Titel</th>
          <th>Erstellt</th>
          <th>Aktualisiert</th>
          <th class="actions">Aktionen</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($posts as $post): ?>
        <tr>
          <td><?= htmlspecialchars($post['title'], ENT_QUOTES, 'UTF-8') ?></td>
          <td><?= htmlspecialchars($post['created_at'], ENT_QUOTES, 'UTF-8') ?></td>
          <td><?= htmlspecialchars($post['updated_at'], ENT_QUOTES, 'UTF-8') ?></td>
          <td class="actions">

            <a class="link" href="<?= htmlspecialchars(path('/admin/posts/' . (int) $post['id'] . '/edit'), ENT_QUOTES, 'UTF-8') ?>">Bearbeiten</a>
            <form
              method="post"
              action="<?= htmlspecialchars(path('/admin/posts/' . (int) $post['id'] . '/delete'), ENT_QUOTES, 'UTF-8') ?>"

              onsubmit="return confirm('Beitrag wirklich löschen?');"
            >
              <button type="submit" class="link danger">Löschen</button>
            </form>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <?php else: ?>
    <div class="empty-state">
      <h3>Noch keine Beiträge</h3>
      <p>Erstelle den ersten Beitrag, um die Seite zu füllen.</p>
    </div>
    <?php endif; ?>
  </div>

  <div class="dashboard-section">
    <div class="dashboard-section-header">
      <h2>Seiten</h2>

      <a class="btn" href="<?= htmlspecialchars(path('/admin/pages/new'), ENT_QUOTES, 'UTF-8') ?>">Neue Seite</a>

    </div>
    <?php if (!empty($pages)): ?>
    <table class="table">
      <thead>
        <tr>
          <th>Titel</th>
          <th>Slug</th>
          <th class="actions">Aktionen</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($pages as $page): ?>
        <tr>
          <td><?= htmlspecialchars($page['title'], ENT_QUOTES, 'UTF-8') ?></td>
          <td><code><?= htmlspecialchars($page['slug'], ENT_QUOTES, 'UTF-8') ?></code></td>
          <td class="actions">

            <a class="link" href="<?= htmlspecialchars(path('/admin/pages/' . (int) $page['id'] . '/edit'), ENT_QUOTES, 'UTF-8') ?>">Bearbeiten</a>
            <form
              method="post"
              action="<?= htmlspecialchars(path('/admin/pages/' . (int) $page['id'] . '/delete'), ENT_QUOTES, 'UTF-8') ?>"

              onsubmit="return confirm('Seite wirklich löschen?');"
            >
              <button type="submit" class="link danger">Löschen</button>
            </form>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <?php else: ?>
    <div class="empty-state">
      <h3>Noch keine Seiten</h3>
      <p>Lege deine erste Inhaltsseite an.</p>
    </div>
    <?php endif; ?>
  </div>

  <div class="dashboard-section">
    <div class="dashboard-section-header">
      <h2>Galerie</h2>

      <a class="btn" href="<?= htmlspecialchars(path('/admin/gallery/new'), ENT_QUOTES, 'UTF-8') ?>">Neuer Eintrag</a>

    </div>
    <?php if (!empty($gallery)): ?>
    <table class="table">
      <thead>
        <tr>
          <th>Titel</th>
          <th>Datei</th>
          <th class="actions">Aktionen</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($gallery as $item): ?>
        <tr>
          <td><?= htmlspecialchars($item['title'], ENT_QUOTES, 'UTF-8') ?></td>

          <td>
            <a
              class="link"
              href="<?= htmlspecialchars(asset('static/uploads/' . $item['filename']), ENT_QUOTES, 'UTF-8') ?>"
              target="_blank"
            >Download</a>
          </td>
          <td class="actions">
            <a class="link" href="<?= htmlspecialchars(path('/admin/gallery/' . (int) $item['id'] . '/edit'), ENT_QUOTES, 'UTF-8') ?>">Bearbeiten</a>
            <form
              method="post"
              action="<?= htmlspecialchars(path('/admin/gallery/' . (int) $item['id'] . '/delete'), ENT_QUOTES, 'UTF-8') ?>"

              onsubmit="return confirm('Eintrag wirklich löschen?');"
            >
              <button type="submit" class="link danger">Löschen</button>
            </form>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <?php else: ?>
    <div class="empty-state">
      <h3>Noch keine Galerie-Elemente</h3>
      <p>Lade Bilder hoch, um deine Galerie zu füllen.</p>
    </div>
    <?php endif; ?>
  </div>

  <div class="dashboard-section">
    <div class="dashboard-section-header">
      <h2>Genetik</h2>

      <a class="btn" href="<?= htmlspecialchars(path('/admin/genetics'), ENT_QUOTES, 'UTF-8') ?>">Gene verwalten</a>

    </div>
    <p>
      Pflege die genetischen Eigenschaften für Bartagamen und Hakennasennattern und nutze sie im
      öffentlichen Genetik-Rechner.
    </p>

    <a class="link" href="<?= htmlspecialchars(path('/genetics'), ENT_QUOTES, 'UTF-8') ?>" target="_blank">Genetik-Bereich ansehen</a>

  </div>
</section>
