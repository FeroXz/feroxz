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
<section class="mx-auto w-full max-w-5xl px-4 sm:px-6 lg:px-8">
    <article class="rounded-3xl border border-white/5 bg-night-900/70 p-8 shadow-lg shadow-black/30">
        <h1 class="text-3xl font-semibold text-white sm:text-4xl"><?= htmlspecialchars($article['title']) ?></h1>
        <div class="mt-4 flex flex-wrap items-center gap-4 text-sm text-slate-300">
            <span class="inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/5 px-3 py-1">
                <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M10 2a.75.75 0 01.75.75v6.5h5.5a.75.75 0 010 1.5h-6.25a.75.75 0 01-.75-.75v-7.25A.75.75 0 0110 2zm-6 3a1 1 0 00-1 1v9.5A2.5 2.5 0 005.5 18h9a2.5 2.5 0 002.5-2.5V11a1 1 0 10-2 0v4.5c0 .552-.448 1-1 1h-9c-.552 0-1-.448-1-1V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                Lesedauer: <?= $readMinutes ?> <?= $readMinutes === 1 ? 'Minute' : 'Minuten' ?>
            </span>
            <?php if (!empty($article['updated_at'])): ?>
                <span>Aktualisiert: <?= date('d.m.Y', strtotime($article['updated_at'])) ?></span>
            <?php endif; ?>
        </div>
        <?php if (!empty($article['summary'])): ?>
            <p class="mt-4 text-base text-slate-200"><?= nl2br(htmlspecialchars($article['summary'])) ?></p>
        <?php endif; ?>
        <?php if (!empty($toc)): ?>
            <aside class="mt-6 rounded-2xl border border-white/10 bg-white/5 p-4 text-sm text-slate-200">
                <h2 class="text-base font-semibold text-white">Inhaltsverzeichnis</h2>
                <ol class="mt-3 space-y-2">
                    <?php foreach ($toc as $item): ?>
                        <li><a class="text-brand-200 hover:underline" href="#<?= htmlspecialchars($item['id']) ?>"><?= htmlspecialchars($item['title']) ?></a></li>
                    <?php endforeach; ?>
                </ol>
            </aside>
        <?php endif; ?>
        <div class="rich-text-content prose prose-invert mt-6 max-w-none text-slate-100">
            <?= render_rich_text($contentWithAnchors) ?>
        </div>
    </article>
</section>
<?php include __DIR__ . '/../partials/footer.php'; ?>

