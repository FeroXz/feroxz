<?php include __DIR__ . '/../partials/header.php'; ?>
<section class="mx-auto w-full max-w-4xl px-4 sm:px-6 lg:px-8">
    <article class="rounded-3xl border border-white/10 bg-night-900/80 p-10 shadow-card">
        <h1 class="text-3xl font-semibold text-white sm:text-4xl"><?= htmlspecialchars($article['title']) ?></h1>
        <?php if (!empty($article['summary'])): ?>
            <p class="mt-3 text-sm text-slate-300"><?= nl2br(htmlspecialchars($article['summary'])) ?></p>
        <?php endif; ?>
        <div class="rich-text-content prose prose-invert mt-6 max-w-none text-slate-100">
            <?= render_rich_text($article['content']) ?>
        </div>
    </article>
</section>
<?php include __DIR__ . '/../partials/footer.php'; ?>

