<!DOCTYPE html>
<html lang="de">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?= htmlspecialchars($pageTitle ?? 'Feroxz CMS', ENT_QUOTES, 'UTF-8') ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="<?= htmlspecialchars(asset('static/css/style.css'), ENT_QUOTES, 'UTF-8') ?>" />
  </head>
  <body>
    <header class="site-header">
      <div class="container header-content">
        <a class="brand" href="<?= htmlspecialchars(path('/'), ENT_QUOTES, 'UTF-8') ?>">Feroxz CMS</a>
        <nav>
          <ul>
            <li><a href="<?= htmlspecialchars(path('/'), ENT_QUOTES, 'UTF-8') ?>">Start</a></li>
            <li><a href="<?= htmlspecialchars(path('/gallery'), ENT_QUOTES, 'UTF-8') ?>">Galerie</a></li>
            <li><a href="<?= htmlspecialchars(path('/genetics'), ENT_QUOTES, 'UTF-8') ?>">Genetik</a></li>
            <?php if (!empty($_SESSION['admin'])): ?>
            <li><a href="<?= htmlspecialchars(path('/admin'), ENT_QUOTES, 'UTF-8') ?>">Admin</a></li>
            <li><a href="<?= htmlspecialchars(path('/admin/logout'), ENT_QUOTES, 'UTF-8') ?>" class="logout">Logout</a></li>
            <?php else: ?>
            <li><a href="<?= htmlspecialchars(path('/admin/login'), ENT_QUOTES, 'UTF-8') ?>">Login</a></li>
            <?php endif; ?>
          </ul>
        </nav>
      </div>
    </header>

    <main class="container">
      <?php if (!empty($flashMessages)): ?>
      <div class="flash-wrapper">
        <?php foreach ($flashMessages as $flash): ?>
        <div class="flash <?= htmlspecialchars($flash['type'], ENT_QUOTES, 'UTF-8') ?>">
          <?= htmlspecialchars($flash['message'], ENT_QUOTES, 'UTF-8') ?>
        </div>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>

      <?= $content ?>
    </main>

    <footer class="site-footer">
      <div class="container">
        <p>&copy; <?= $currentYear ?> Feroxz CMS. Alle Rechte vorbehalten.</p>
      </div>
    </footer>
  </body>
</html>
