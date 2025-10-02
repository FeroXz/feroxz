<?php
$wordCount = str_word_count(strip_tags($article['content'] ?? ''));
$readMinutes = max(1, (int)ceil($wordCount / 200));
$toc = [];
$contentWithAnchors = $article['content'] ?? '';
if ($contentWithAnchors) {
    $contentWithAnchors = preg_replace_callback('/<h2([^>]*)>(.*?)<\/h2>/i', function ($matches) use (&$toc) {
        $text = trim(strip_tags($matches[2]));
        $id = slugify($text);
        $toc[] = ['id' => $id, 'title' => $text];
        $attributes = $matches[1];
        if (stripos($attributes, 'id=') === false) {
            $attributes .= ' id="' . htmlspecialchars($id, ENT_QUOTES) . '"';
        }
        return '<h2' . $attributes . '>' . $matches[2] . '</h2>';
    }, $contentWithAnchors);
}
?>
<?php include __DIR__ . '/../partials/header.php'; ?>
<section class="section">
    <div class="section__inner">
        <article class="article-shell">
            <header>
                <h1><?= htmlspecialchars($article['title']) ?></h1>
                <div class="card__meta">
                    <span>Lesedauer: <?= $readMinutes ?> <?= $readMinutes === 1 ? 'Minute' : 'Minuten' ?></span>
                    <?php if (!empty($article['updated_at'])): ?>
                        <span>Aktualisiert: <?= date('d.m.Y', strtotime($article['updated_at'])) ?></span>
                    <?php endif; ?>
                </div>
                <?php if (!empty($article['summary'])): ?>
                    <p class="section-header__description"><?= nl2br(htmlspecialchars($article['summary'])) ?></p>
                <?php endif; ?>
            </header>
            <?php if (!empty($toc)): ?>
                <aside class="highlight-card" aria-label="Inhaltsverzeichnis">
                    <strong>Inhaltsverzeichnis</strong>
                    <ol>
                        <?php foreach ($toc as $item): ?>
                            <li><a href="#<?= htmlspecialchars($item['id']) ?>"><?= htmlspecialchars($item['title']) ?></a></li>
                        <?php endforeach; ?>
                    </ol>
                </aside>
            <?php endif; ?>
            <div class="content-prose">
                <?= render_rich_text($contentWithAnchors) ?>
            </div>
        </article>
    </div>
</section>
<?php include __DIR__ . '/../partials/footer.php'; ?>
