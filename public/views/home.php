<?php include __DIR__ . '/partials/header.php'; ?>
<section class="relative mx-auto w-full max-w-7xl px-4 sm:px-6 lg:px-8">
    <div class="absolute inset-x-12 -top-20 -z-10 h-72 rounded-3xl bg-gradient-to-r from-brand-600/30 via-brand-400/20 to-moss-400/30 blur-3xl"></div>
    <div class="grid gap-10 overflow-hidden rounded-3xl border border-white/10 bg-night-900/70 p-10 shadow-card lg:grid-cols-[1.1fr_0.9fr]">
        <div class="space-y-6">
            <span class="inline-flex items-center gap-2 rounded-full border border-brand-400/40 bg-brand-500/10 px-3 py-1 text-xs font-semibold uppercase tracking-[0.25em] text-brand-200">FeroxZ</span>
            <h1 class="text-4xl font-semibold leading-tight text-white sm:text-5xl">
                <?= htmlspecialchars($settings['site_title'] ?? APP_NAME) ?>
            </h1>
            <div class="rich-text-content prose prose-invert max-w-none text-lg text-slate-200">
                <?= render_rich_text($settings['hero_intro'] ?? '') ?>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="<?= BASE_URL ?>/index.php?route=genetics" class="btn">Genetik-Rechner öffnen</a>
                <a href="<?= BASE_URL ?>/index.php?route=care-guide" class="btn btn-secondary">Pflegewissen entdecken</a>
            </div>
            <dl class="grid gap-4 text-sm text-slate-300 sm:grid-cols-3">
                <div class="rounded-2xl border border-white/10 bg-night-900/60 p-4">
                    <dt class="text-xs uppercase tracking-[0.2em] text-slate-400">Arten</dt>
                    <dd class="mt-2 text-xl font-semibold text-white">Heterodon &amp; Pogona</dd>
                </div>
                <div class="rounded-2xl border border-white/10 bg-night-900/60 p-4">
                    <dt class="text-xs uppercase tracking-[0.2em] text-slate-400">Genetik-Datenbank</dt>
                    <dd class="mt-2 text-xl font-semibold text-white">MorphMarket-inspiriert</dd>
                </div>
                <div class="rounded-2xl border border-white/10 bg-night-900/60 p-4">
                    <dt class="text-xs uppercase tracking-[0.2em] text-slate-400">Pflegeleitfäden</dt>
                    <dd class="mt-2 text-xl font-semibold text-white">Wiki für Halter</dd>
                </div>
            </dl>
        </div>
        <div class="relative">
            <div class="absolute -top-20 right-8 h-32 w-32 rounded-full bg-brand-500/20 blur-3xl"></div>
            <div class="rounded-3xl border border-white/10 bg-night-900/80 p-8 shadow-card">
                <h2 class="text-xl font-semibold text-white">Digitale Tierakten</h2>
                <p class="mt-3 text-sm leading-relaxed text-slate-300">
                    Hinterlegen Sie Genetik, Morphs, Gesundheitsdaten und besondere Merkmale pro Tier. Für Zuchtpläne
                    lassen sich reale mit virtuellen Elterntieren kombinieren.
                </p>
                <ul class="mt-6 space-y-3 text-sm text-slate-200">
                    <li class="flex items-center gap-3">
                        <span class="flex h-8 w-8 items-center justify-center rounded-full bg-brand-500/20 text-brand-200">1</span>
                        <span>Elterntiere auswählen oder virtuell anlegen.</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <span class="flex h-8 w-8 items-center justify-center rounded-full bg-brand-500/20 text-brand-200">2</span>
                        <span>Genetik in MorphMarket-Notation erfassen.</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <span class="flex h-8 w-8 items-center justify-center rounded-full bg-brand-500/20 text-brand-200">3</span>
                        <span>Ergebnisse und mögliche Hets sofort berechnen.</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>

<?php if (!empty($animals)): ?>
<section class="mx-auto mt-20 w-full max-w-7xl px-4 sm:px-6 lg:px-8">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-2xl font-semibold text-white sm:text-3xl">Bestand im Fokus</h2>
            <p class="text-sm text-slate-400">Ausgewählte Tiere mit dokumentierter Genetik</p>
        </div>
        <span class="rounded-full border border-white/10 px-4 py-1 text-xs uppercase tracking-[0.3em] text-slate-400">Kuratiert</span>
    </div>
    <div class="mt-8 grid gap-6 sm:grid-cols-2 xl:grid-cols-3">
        <?php foreach ($animals as $animal): ?>
            <article class="group flex h-full flex-col overflow-hidden rounded-3xl border border-white/10 bg-night-900/75 shadow-card transition hover:border-brand-400/70 hover:shadow-glow">
                <?php if (!empty($animal['image_path'])): ?>
                    <img src="<?= BASE_URL . '/' . htmlspecialchars($animal['image_path']) ?>" alt="<?= htmlspecialchars($animal['name']) ?>" class="h-52 w-full object-cover" loading="lazy">
                <?php endif; ?>
                <div class="flex flex-1 flex-col gap-3 p-6">
                    <h3 class="text-xl font-semibold text-white">
                        <?= htmlspecialchars($animal['name']) ?>
                        <?php if (!empty($animal['is_piebald'])): ?>
                            <span class="ml-2 inline-flex items-center justify-center rounded-full border border-brand-400 bg-brand-500/25 px-2 py-0.5 text-xs font-semibold uppercase tracking-[0.3em] text-brand-100" title="Geschecktes Tier" aria-label="Geschecktes Tier">Gescheckt</span>
                        <?php endif; ?>
                    </h3>
                    <p class="text-sm text-slate-300"><?= htmlspecialchars($animal['species']) ?></p>
                    <?php if (!empty($animal['genetics'])): ?>
                        <div class="rounded-2xl border border-brand-400/40 bg-brand-500/10 px-3 py-2 text-sm text-brand-100">
                            <span class="font-semibold uppercase tracking-[0.2em]">Genetik:</span>
                            <?= htmlspecialchars($animal['genetics']) ?>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($animal['special_notes'])): ?>
                        <div class="rich-text-content prose prose-invert max-w-none text-sm text-slate-200">
                            <?= render_rich_text($animal['special_notes']) ?>
                        </div>
                    <?php endif; ?>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>

<section class="mx-auto mt-20 w-full max-w-7xl px-4 sm:px-6 lg:px-8">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h2 class="text-2xl font-semibold text-white sm:text-3xl">Tiervermittlung</h2>
            <p class="text-sm text-slate-400">Vertrauensvolle Abgabe mit Transparenz</p>
        </div>
        <?php if (!empty($settings['contact_email'])): ?>
            <a href="mailto:<?= htmlspecialchars($settings['contact_email']) ?>" class="btn">Kontakt aufnehmen</a>
        <?php endif; ?>
    </div>
    <div class="rich-text-content prose prose-invert mt-6 max-w-none text-slate-200">
        <?= render_rich_text($settings['adoption_intro'] ?? '') ?>
    </div>
    <div class="mt-10 grid gap-6 sm:grid-cols-2 xl:grid-cols-3">
        <?php foreach ($listings as $listing): ?>
            <article class="flex h-full flex-col rounded-3xl border border-white/10 bg-night-900/75 p-6 shadow-card transition hover:border-brand-400/70 hover:shadow-glow">
                <?php if (!empty($listing['image_path'])): ?>
                    <img src="<?= BASE_URL . '/' . htmlspecialchars($listing['image_path']) ?>" alt="<?= htmlspecialchars($listing['title']) ?>" class="mb-4 h-48 w-full rounded-2xl object-cover" loading="lazy">
                <?php endif; ?>
                <h3 class="text-xl font-semibold text-white"><?= htmlspecialchars($listing['title']) ?></h3>
                <?php if (!empty($listing['species'])): ?>
                    <p class="text-sm text-slate-300"><?= htmlspecialchars($listing['species']) ?></p>
                <?php endif; ?>
                <?php if (!empty($listing['genetics'])): ?>
                    <p class="mt-2 rounded-xl border border-brand-400/40 bg-brand-500/10 px-3 py-2 text-sm text-brand-100">
                        <span class="font-semibold uppercase tracking-[0.2em]">Genetik:</span>
                        <?= htmlspecialchars($listing['genetics']) ?>
                    </p>
                <?php endif; ?>
                <?php if (!empty($listing['price'])): ?>
                    <p class="mt-2 text-sm text-slate-200"><strong>Preis:</strong> <?= htmlspecialchars($listing['price']) ?></p>
                <?php endif; ?>
                <?php if (!empty($listing['description'])): ?>
                    <div class="rich-text-content prose prose-invert mt-3 max-w-none text-sm text-slate-200">
                        <?= render_rich_text($listing['description']) ?>
                    </div>
                <?php endif; ?>
                <?php if (!empty($settings['contact_email'])): ?>
                    <a class="mt-4 inline-flex items-center justify-center rounded-full border border-brand-400/60 bg-brand-500/10 px-4 py-2 text-sm font-semibold text-brand-100 transition hover:border-brand-300 hover:bg-brand-500/20" href="mailto:<?= htmlspecialchars($settings['contact_email']) ?>?subject=Anfrage%20<?= urlencode($listing['title']) ?>">Direkt anfragen</a>
                <?php endif; ?>
            </article>
        <?php endforeach; ?>
    </div>
</section>

<?php if (!empty($latestNews)): ?>
<section class="mx-auto mt-20 w-full max-w-7xl px-4 sm:px-6 lg:px-8">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h2 class="text-2xl font-semibold text-white sm:text-3xl">Neuigkeiten</h2>
            <p class="text-sm text-slate-400">Aktuelle Meldungen aus Pflege und Zucht</p>
        </div>
        <a class="btn btn-secondary" href="<?= BASE_URL ?>/index.php?route=news">Alle Meldungen anzeigen</a>
    </div>
    <div class="mt-8 grid gap-6 md:grid-cols-3">
        <?php foreach ($latestNews as $post): ?>
            <article class="flex h-full flex-col rounded-3xl border border-white/10 bg-night-900/75 p-6 shadow-card transition hover:border-brand-400/70 hover:shadow-glow">
                <h3 class="text-xl font-semibold text-white"><?= htmlspecialchars($post['title']) ?></h3>
                <?php if (!empty($post['published_at'])): ?>
                    <p class="text-xs uppercase tracking-[0.3em] text-slate-400">Veröffentlicht am <?= date('d.m.Y', strtotime($post['published_at'])) ?></p>
                <?php endif; ?>
                <?php if (!empty($post['excerpt'])): ?>
                    <p class="mt-3 text-sm text-slate-200"><?= nl2br(htmlspecialchars($post['excerpt'])) ?></p>
                <?php endif; ?>
                <a class="mt-4 inline-flex items-center gap-2 rounded-full border border-brand-400/60 bg-brand-500/10 px-4 py-2 text-sm font-semibold text-brand-100 transition hover:border-brand-300 hover:bg-brand-500/20" href="<?= BASE_URL ?>/index.php?route=news&amp;slug=<?= urlencode($post['slug']) ?>">Details ansehen</a>
            </article>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>

<?php if (!empty($careHighlights)): ?>
<section class="mx-auto mt-20 w-full max-w-7xl px-4 sm:px-6 lg:px-8">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h2 class="text-2xl font-semibold text-white sm:text-3xl">Pflegewissen</h2>
            <p class="text-sm text-slate-400">Fundierte Leitfäden für verantwortungsvolle Haltung</p>
        </div>
        <a class="btn" href="<?= BASE_URL ?>/index.php?route=care-guide">Zur Wissenssammlung</a>
    </div>
    <div class="mt-8 grid gap-6 md:grid-cols-3">
        <?php foreach ($careHighlights as $article): ?>
            <article class="flex h-full flex-col rounded-3xl border border-white/10 bg-night-900/75 p-6 shadow-card transition hover:border-brand-400/70 hover:shadow-glow">
                <h3 class="text-xl font-semibold text-white"><?= htmlspecialchars($article['title']) ?></h3>
                <?php if (!empty($article['summary'])): ?>
                    <p class="mt-3 text-sm text-slate-200"><?= nl2br(htmlspecialchars($article['summary'])) ?></p>
                <?php endif; ?>
                <a class="mt-auto inline-flex items-center gap-2 rounded-full border border-white/15 px-4 py-2 text-sm font-semibold text-slate-100 transition hover:border-brand-400 hover:text-brand-100" href="<?= BASE_URL ?>/index.php?route=care-article&amp;slug=<?= urlencode($article['slug']) ?>">Leitfaden öffnen</a>
            </article>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>

<?php include __DIR__ . '/partials/footer.php'; ?>
