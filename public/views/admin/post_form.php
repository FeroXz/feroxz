<section class="editor">
  <div class="editor-header">
    <h1><?= htmlspecialchars($heading, ENT_QUOTES, 'UTF-8') ?></h1>

    <a class="link" href="<?= htmlspecialchars(path('/admin'), ENT_QUOTES, 'UTF-8') ?>">Zurück</a>

  </div>
  <form method="post" class="form">
    <label for="title">Titel</label>
    <input
      type="text"
      id="title"
      name="title"
      value="<?= htmlspecialchars($post['title'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
      required
    />

    <label for="content">Inhalt</label>
    <textarea id="content" name="content" rows="10" required><?= htmlspecialchars($post['content'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>

    <button class="btn" type="submit">Speichern</button>
  </form>
</section>
