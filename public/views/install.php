<section class="install">
  <h1>Installationsassistent</h1>
  <p>
    Willkommen beim Feroxz CMS. Dieser Assistent hilft dir beim Einrichten eines ersten
    Administratorzugangs und prüft, ob dein Webspace alle Voraussetzungen erfüllt.
  </p>

  <?php $phpRequirement = $requirements['php'] ?? ['met' => false, 'label' => 'PHP-Version', 'current' => PHP_VERSION]; ?>
  <?php $extensions = $requirements['extensions'] ?? []; ?>
  <?php $paths = $requirements['paths'] ?? []; ?>

  <h2>Systemvoraussetzungen</h2>
  <div class="requirement-group">
    <h3>PHP</h3>
    <ul class="requirement-list">
      <li class="requirement-item <?= !empty($phpRequirement['met']) ? 'ok' : 'error' ?>">
        <strong><?= htmlspecialchars($phpRequirement['label'], ENT_QUOTES, 'UTF-8') ?></strong>
        <span>
          <?= !empty($phpRequirement['met']) ? 'erfüllt' : 'nicht erfüllt' ?>
          (aktuell: <?= htmlspecialchars((string) ($phpRequirement['current'] ?? PHP_VERSION), ENT_QUOTES, 'UTF-8') ?>)
        </span>
      </li>
    </ul>
  </div>

  <div class="requirement-group">
    <h3>PHP-Erweiterungen</h3>
    <ul class="requirement-list">
      <?php foreach ($extensions as $key => $extension): ?>
      <li class="requirement-item <?= !empty($extension['met']) ? 'ok' : (!empty($extension['required']) ? 'error' : 'warning') ?>">
        <strong><?= htmlspecialchars($extension['label'], ENT_QUOTES, 'UTF-8') ?></strong>
        <span>
          <?php if (!empty($extension['met'])): ?>
          vorhanden
          <?php elseif (!empty($extension['required'])): ?>
          erforderlich
          <?php else: ?>
          empfohlen
          <?php endif; ?>
        </span>
      </li>
      <?php endforeach; ?>
    </ul>
  </div>

  <div class="requirement-group">
    <h3>Datei- und Ordnerrechte</h3>
    <ul class="requirement-list">
      <?php foreach ($paths as $path): ?>
      <li class="requirement-item <?= !empty($path['met']) ? 'ok' : 'error' ?>">
        <strong><?= htmlspecialchars($path['label'], ENT_QUOTES, 'UTF-8') ?></strong>
        <span>
          <?php if (!empty($path['met'])): ?>
          in Ordnung
          <?php else: ?>
          bitte prüfen (Pfad: <?= htmlspecialchars($path['path'], ENT_QUOTES, 'UTF-8') ?>)
          <?php endif; ?>
        </span>
      </li>
      <?php endforeach; ?>
    </ul>
  </div>

  <?php if (!empty($databaseError)): ?>
  <div class="flash danger">
    Die Datenbankverbindung konnte nicht hergestellt werden: <?= htmlspecialchars($databaseError, ENT_QUOTES, 'UTF-8') ?>
  </div>
  <?php endif; ?>

  <h2>Administrator anlegen</h2>
  <p>
    Sobald alle Voraussetzungen erfüllt sind, kannst du hier deinen ersten Administratorzugang erstellen.
  </p>

  <form method="post" class="form install-form">
    <div class="form-group">
      <label for="username">Benutzername</label>
      <input
        type="text"
        id="username"
        name="username"
        required
        value="<?= htmlspecialchars($formData['username'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
      />
    </div>
    <div class="form-group">
      <label for="password">Passwort</label>
      <input type="password" id="password" name="password" required minlength="8" />
    </div>
    <div class="form-group">
      <label for="password_confirmation">Passwort bestätigen</label>
      <input type="password" id="password_confirmation" name="password_confirmation" required minlength="8" />
    </div>
    <button type="submit" <?= $canInstall ? '' : 'disabled' ?>>Administrator erstellen</button>
  </form>

  <?php if (!$canInstall): ?>
  <p class="hint">
    Bitte behebe alle rot markierten Punkte, bevor du den Installer erneut versuchst.
  </p>
  <?php endif; ?>
</section>
