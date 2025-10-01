<?php include __DIR__ . '/../partials/header.php'; ?>
<section class="mx-auto w-full max-w-7xl px-4 sm:px-6 lg:px-8">
    <header class="max-w-3xl">
        <span class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/5 px-3 py-1 text-xs font-semibold uppercase tracking-[0.25em] text-slate-300">News</span>
        <h1 class="mt-4 text-3xl font-semibold text-white sm:text-4xl">Neuigkeiten</h1>
        <p class="mt-2 text-sm text-slate-300">Aktuelle Updates aus dem FeroxZ Center.</p>
    </header>
    <div class="mt-12 grid gap-6 md:grid-cols-2 xl:grid-cols-3">
        <?php foreach ($newsPosts as $post): ?>
            <article class="flex h-full flex-col rounded-3xl border border-white/10 bg-night-900/75 p-6 shadow-card transition hover:border-brand-400/70 hover:shadow-glow">
                <h2 class="text-xl font-semibold text-white"><?= htmlspecialchars($post['title']) ?></h2>
                <?php if (!empty($post['published_at'])): ?>
                    <p class="text-xs uppercase tracking-[0.3em] text-slate-400">Veröffentlicht am <?= date('d.m.Y H:i', strtotime($post['published_at'])) ?></p>
                <?php endif; ?>
                <?php if (!empty($post['excerpt'])): ?>
                    <p class="mt-3 text-sm text-slate-200"><?= nl2br(htmlspecialchars($post['excerpt'])) ?></p>
                <?php endif; ?>
                <a class="mt-auto inline-flex items-center gap-2 rounded-full border border-brand-400/60 bg-brand-500/10 px-4 py-2 text-sm font-semibold text-brand-100 transition hover:border-brand-300 hover:bg-brand-500/20" href="<?= BASE_URL ?>/index.php?route=news&amp;slug=<?= urlencode($post['slug']) ?>">Weiterlesen</a>
            </article>
        <?php endforeach; ?>
    </div>
</section>
<?php include __DIR__ . '/../partials/footer.php'; ?>

