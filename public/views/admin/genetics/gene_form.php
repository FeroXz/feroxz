<section class="admin genetics-gene-form">
  <header class="page-header">
    <h1>
      <?php if ($gene === null): ?>
      Neues Gen anlegen – <?= htmlspecialchars($species['name'], ENT_QUOTES, 'UTF-8') ?>
      <?php else: ?>
      Gen bearbeiten – <?= htmlspecialchars($gene['name'], ENT_QUOTES, 'UTF-8') ?>
      <?php endif; ?>
    </h1>
    <div class="actions">

      <a
        class="button secondary"
        href="<?= htmlspecialchars(path('/admin/genetics/species/' . $species['slug']), ENT_QUOTES, 'UTF-8') ?>"
      >Zurück</a>

    </div>
  </header>

  <form method="post" class="form">
    <div class="form-group">
      <label for="name">Name</label>
      <input
        type="text"
        id="name"
        name="name"
        value="<?= htmlspecialchars($gene['name'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
        required
      />
    </div>

    <div class="form-group">
      <label for="slug">Slug</label>
      <input
        type="text"
        id="slug"
        name="slug"
        value="<?= htmlspecialchars($gene['slug'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
        placeholder="z. B. albino"
      />
      <small>Wird für URLs und den Rechner verwendet. Leer lassen, um den Namen zu verwenden.</small>
    </div>

    <div class="form-group">
      <label for="inheritance_type">Vererbung</label>
      <select id="inheritance_type" name="inheritance_type" required>
        <option value="">Bitte wählen</option>
        <?php foreach ($inheritanceLabels as $value => $label): ?>
        <option value="<?= htmlspecialchars($value, ENT_QUOTES, 'UTF-8') ?>" <?= isset($gene['inheritance_type']) && $gene['inheritance_type'] === $value ? 'selected' : '' ?>>
          <?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?>
        </option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="form-grid">
      <div class="form-group">
        <label for="normal_label">0 Kopien (Wildtyp)</label>
        <input
          type="text"
          id="normal_label"
          name="normal_label"
          value="<?= htmlspecialchars($gene['normal_label'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
          required
        />
      </div>
      <div class="form-group">
        <label for="heterozygous_label">1 Kopie (heterozygot)</label>
        <input
          type="text"
          id="heterozygous_label"
          name="heterozygous_label"
          value="<?= htmlspecialchars($gene['heterozygous_label'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
          required
        />
      </div>
      <div class="form-group">
        <label for="homozygous_label">2 Kopien (homozygot)</label>
        <input
          type="text"
          id="homozygous_label"
          name="homozygous_label"
          value="<?= htmlspecialchars($gene['homozygous_label'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
          required
        />
      </div>
    </div>

    <div class="form-group">
      <label for="description">Beschreibung</label>
      <textarea
        id="description"
        name="description"
        rows="4"
      ><?= htmlspecialchars($gene['description'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
    </div>

    <div class="form-actions">
      <button class="button" type="submit">Speichern</button>
    </div>
  </form>
</section>
