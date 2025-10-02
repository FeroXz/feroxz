<?php include __DIR__ . '/../partials/header.php'; ?>
<section class="section">
    <div class="section__inner">
        <article class="article-shell">
            <header>
                <h1><?= htmlspecialchars($page['title']) ?></h1>
            </header>
            <div class="content-prose">
                <?= render_rich_text($page['content']) ?>
            </div>
        </article>
    </div>
</section>
<?php include __DIR__ . '/../partials/footer.php'; ?>
