<section class="editor">
  <div class="editor-header">
    <h1><?= htmlspecialchars($heading, ENT_QUOTES, 'UTF-8') ?></h1>

    <a class="link" href="<?= htmlspecialchars(path('/admin'), ENT_QUOTES, 'UTF-8') ?>">Zurück</a>

  </div>
  <form method="post" class="form" enctype="multipart/form-data">
    <label for="title">Titel</label>
    <input
      type="text"
      id="title"
      name="title"
      value="<?= htmlspecialchars($item['title'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
      required
    />

    <label for="description">Beschreibung</label>
    <textarea id="description" name="description" rows="5"><?= htmlspecialchars($item['description'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>

    <label for="file">Datei</label>
    <?php if (!empty($item['filename'])): ?>
    <p class="hint">
      Aktuelle Datei:

      <a
        class="link"
        href="<?= htmlspecialchars(asset('static/uploads/' . $item['filename']), ENT_QUOTES, 'UTF-8') ?>"
        target="_blank"
      >Ansehen</a>

    </p>
    <?php endif; ?>
    <input type="file" id="file" name="file" <?= empty($item) ? 'required' : '' ?> />

    <button class="btn" type="submit">Speichern</button>
  </form>
</section>
