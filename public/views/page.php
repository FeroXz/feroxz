<section class="page">
  <header class="page-header">
    <h1><?= htmlspecialchars($page['title'], ENT_QUOTES, 'UTF-8') ?></h1>
  </header>
  <article class="page-content">
    <?= nl2br(htmlspecialchars($page['content'], ENT_QUOTES, 'UTF-8')) ?>
  </article>
</section>
