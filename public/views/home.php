<?php include __DIR__ . '/partials/header.php'; ?>
<section class="mx-auto w-full max-w-7xl px-4 sm:px-6 lg:px-8">
    <div class="grid gap-10 rounded-3xl border border-white/5 bg-night-900/70 p-8 shadow-glow shadow-brand-600/20 lg:grid-cols-[1.2fr_1fr]">
        <div class="flex flex-col justify-between gap-8">
            <div class="space-y-5">
                <span class="inline-flex w-fit items-center gap-2 rounded-full border border-brand-400/60 bg-brand-500/10 px-4 py-2 text-xs font-semibold uppercase tracking-[0.28em] text-brand-100">FeroxZ Reptile Center</span>
                <h1 class="text-3xl font-semibold text-white sm:text-4xl lg:text-5xl">
                    Fachwissen &amp; Verantwortung für Bartagamen
                </h1>
                <div class="rich-text-content prose prose-invert max-w-none text-lg leading-relaxed">
                    <?= render_rich_text($settings['hero_intro'] ?? '') ?>
                </div>
                <ul class="grid gap-3 text-sm text-slate-200 sm:grid-cols-3">
                    <li class="flex items-start gap-2 rounded-2xl border border-white/5 bg-white/5 p-3">
                        <span aria-hidden="true" class="mt-0.5 h-2.5 w-2.5 rounded-full bg-brand-400"></span>
                        <span>Transparente Haltungs- &amp; Übergabekriterien</span>
                    </li>
                    <li class="flex items-start gap-2 rounded-2xl border border-white/5 bg-white/5 p-3">
                        <span aria-hidden="true" class="mt-0.5 h-2.5 w-2.5 rounded-full bg-brand-400"></span>
                        <span>Evidenzbasierte Pflegeleitfäden</span>
                    </li>
                    <li class="flex items-start gap-2 rounded-2xl border border-white/5 bg-white/5 p-3">
                        <span aria-hidden="true" class="mt-0.5 h-2.5 w-2.5 rounded-full bg-brand-400"></span>
                        <span>Genetik-Tools für verantwortungsvolle Zucht</span>
                    </li>
                </ul>
            </div>
            <div class="grid gap-3 sm:grid-cols-3">
                <a href="<?= BASE_URL ?>/index.php?route=care-guide" class="flex h-full items-center justify-between rounded-2xl border border-brand-400/60 bg-brand-500/15 px-4 py-3 text-sm font-semibold text-brand-100 shadow-glow transition hover:border-brand-300 hover:bg-brand-500/25">
                    Pflegeleitfaden Bartagame
                    <span aria-hidden="true">→</span>
                </a>
                <?php if (!empty($featureGeneticsTeaser)): ?>
                    <a href="<?= BASE_URL ?>/index.php?route=genetics" class="flex h-full items-center justify-between rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm font-semibold text-slate-100 transition hover:border-brand-300 hover:text-brand-100">
                        Genetik-Rechner
                        <span aria-hidden="true">→</span>
                    </a>
                <?php endif; ?>
                <a href="<?= BASE_URL ?>/index.php?route=adoption" class="flex h-full items-center justify-between rounded-2xl border border-brand-400/40 bg-brand-500/10 px-4 py-3 text-sm font-semibold text-brand-100 transition hover:border-brand-300 hover:bg-brand-500/20">
                    Abgabetiere ansehen
                    <span aria-hidden="true">→</span>
                </a>
            </div>
        </div>
        <div class="grid content-between gap-6">
            <div class="rounded-3xl border border-brand-400/30 bg-brand-500/10 p-6 text-sm text-brand-100 shadow-inner shadow-brand-600/10">
                <h2 class="text-lg font-semibold text-brand-50">Aktuelle Kennzahlen</h2>
                <dl class="mt-4 grid gap-4">
                    <div class="flex items-center justify-between">
                        <dt>Pflegeguides online</dt>
                        <dd class="text-2xl font-semibold text-brand-200"><?= count($careHighlights) ?></dd>
                    </div>
                    <div class="flex items-center justify-between">
                        <dt>Aktive Tiere im Bestand</dt>
                        <dd class="text-2xl font-semibold text-brand-200"><?= count($animals) ?></dd>
                    </div>
                    <div class="flex items-center justify-between">
                        <dt>Offene Vermittlungen</dt>
                        <dd class="text-2xl font-semibold text-brand-200"><?= count($listings) ?></dd>
                    </div>
                </dl>
            </div>
            <div class="rounded-3xl border border-white/10 bg-white/5 p-6 text-sm text-slate-200">
                <h2 class="text-lg font-semibold text-white">Kontakt</h2>
                <p class="mt-2 leading-relaxed">
                    Bei Fragen zur Haltung, Übergabe oder zu genetischen Kombinationen erreichen Sie uns unter
                    <a class="text-brand-200 underline" href="mailto:<?= htmlspecialchars($settings['contact_email'] ?? CONTACT_EMAIL) ?>"><?= htmlspecialchars($settings['contact_email'] ?? CONTACT_EMAIL) ?></a>.
                </p>
            </div>
        </div>
    </div>
</section>

<?php if (!empty($animals)): ?>
<section class="mx-auto mt-16 w-full max-w-7xl px-4 sm:px-6 lg:px-8">
    <div class="flex items-center justify-between">
        <h2 class="text-2xl font-semibold text-white sm:text-3xl">Unsere Highlights</h2>
        <span class="text-sm text-slate-400">Ausgewählte Tiere aus dem Bestand</span>
    </div>
    <div class="mt-8 grid gap-6 sm:grid-cols-2 xl:grid-cols-3">
        <?php foreach ($animals as $animal): ?>
            <article class="group flex h-full flex-col rounded-3xl border border-white/5 bg-night-900/70 shadow-xl shadow-black/40 transition hover:border-brand-400/60 hover:shadow-glow">
                <?php if (!empty($animal['image_path'])): ?>
                    <?= render_responsive_picture($animal['image_path'], $animal['name'] . ' – ' . $animal['species'], [
                        'class' => 'h-52 w-full rounded-t-3xl object-cover',
                        'sizes' => '(max-width: 768px) 100vw, 360px',
                    ]) ?>
                <?php endif; ?>
                <div class="flex flex-1 flex-col gap-3 p-6">
                    <h3 class="text-xl font-semibold text-white">
                        <?= htmlspecialchars($animal['name']) ?>
                        <?php if (!empty($animal['is_piebald'])): ?>
                            <span class="ml-2 inline-flex items-center justify-center rounded-full border border-brand-400 bg-brand-500/20 px-2 py-0.5 text-xs font-semibold uppercase tracking-wider text-brand-100" title="Geschecktes Tier" aria-label="Geschecktes Tier">Gescheckt</span>
                        <?php endif; ?>
                    </h3>
                    <p class="text-sm text-slate-300"><?= htmlspecialchars($animal['species']) ?></p>
                    <?php if (!empty($animal['genetics'])): ?>
                        <div class="rounded-2xl border border-brand-400/30 bg-brand-500/5 px-3 py-2 text-sm text-brand-100">
                            <span class="font-semibold uppercase tracking-wide">Genetik:</span>
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

<section class="mx-auto mt-16 w-full max-w-7xl px-4 sm:px-6 lg:px-8">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h2 class="text-2xl font-semibold text-white sm:text-3xl">Tiervermittlung</h2>
            <p class="text-sm text-slate-400">Aktuelle Vermittlungstiere und Kontakte</p>
        </div>
        <?php if (!empty($settings['contact_email'])): ?>
            <a href="mailto:<?= htmlspecialchars($settings['contact_email']) ?>" class="inline-flex items-center gap-2 rounded-full border border-brand-400/60 bg-brand-500/10 px-4 py-2 text-sm font-semibold text-brand-100 shadow-glow transition hover:border-brand-300 hover:bg-brand-500/20">
                Kontakt aufnehmen
            </a>
        <?php endif; ?>
    </div>
    <div class="rich-text-content prose prose-invert mt-6 max-w-none text-slate-200">
        <?= render_rich_text($settings['adoption_intro'] ?? '') ?>
    </div>
    <div class="mt-10 grid gap-6 sm:grid-cols-2 xl:grid-cols-3">
        <?php foreach ($listings as $listing): ?>
            <article class="flex h-full flex-col rounded-3xl border border-white/5 bg-night-900/70 p-6 shadow-lg shadow-black/40 transition hover:border-brand-400/60 hover:shadow-glow">
                <?php if (!empty($listing['image_path'])): ?>
                    <?= render_responsive_picture($listing['image_path'], $listing['title'], [
                        'class' => 'mb-4 h-48 w-full rounded-2xl object-cover',
                        'sizes' => '(max-width: 768px) 100vw, 320px',
                    ]) ?>
                <?php endif; ?>
                <h3 class="text-xl font-semibold text-white"><?= htmlspecialchars($listing['title']) ?></h3>
                <?php if (!empty($listing['species'])): ?>
                    <p class="text-sm text-slate-300"><?= htmlspecialchars($listing['species']) ?></p>
                <?php endif; ?>
                <?php if (!empty($listing['genetics'])): ?>
                    <p class="mt-2 rounded-xl border border-brand-400/40 bg-brand-500/10 px-3 py-2 text-sm text-brand-100">
                        <span class="font-semibold uppercase tracking-wide">Genetik:</span>
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
                    <a class="mt-4 inline-flex items-center justify-center rounded-full border border-brand-400/60 bg-brand-500/10 px-4 py-2 text-sm font-semibold text-brand-100 shadow-glow transition hover:border-brand-300 hover:bg-brand-500/20" href="mailto:<?= htmlspecialchars($settings['contact_email']) ?>?subject=Anfrage%20<?= urlencode($listing['title']) ?>">Direkt anfragen</a>
                <?php endif; ?>
            </article>
        <?php endforeach; ?>
    </div>
</section>
<?php if (!empty($latestNews)): ?>
<section class="mx-auto mt-16 w-full max-w-7xl px-4 sm:px-6 lg:px-8">
    <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h2 class="text-2xl font-semibold text-white sm:text-3xl">Neuigkeiten</h2>
            <p class="text-sm text-slate-400">Aktuelle Meldungen aus Verein und Bestand</p>
        </div>
        <a class="inline-flex items-center gap-2 rounded-full border border-white/10 px-4 py-2 text-sm font-semibold text-slate-100 transition hover:border-brand-400 hover:text-brand-100" href="<?= BASE_URL ?>/index.php?route=news">Alle Meldungen anzeigen</a>
    </div>
    <div class="mt-8 grid gap-6 md:grid-cols-3">
        <?php foreach ($latestNews as $post): ?>
            <article class="flex h-full flex-col rounded-3xl border border-white/5 bg-night-900/70 p-6 shadow-lg shadow-black/40 transition hover:border-brand-400/60 hover:shadow-glow">
                <h3 class="text-xl font-semibold text-white"><?= htmlspecialchars($post['title']) ?></h3>
                <?php if (!empty($post['published_at'])): ?>
                    <p class="text-sm text-slate-400"><?= date('d.m.Y', strtotime($post['published_at'])) ?></p>
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
<section class="mx-auto mt-16 w-full max-w-7xl px-4 sm:px-6 lg:px-8">
    <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h2 class="text-2xl font-semibold text-white sm:text-3xl">Pflegewissen</h2>
            <p class="text-sm text-slate-400">Vertiefende Artikel für verantwortungsvolle Haltung</p>
        </div>
        <a class="inline-flex items-center gap-2 rounded-full border border-brand-400/60 bg-brand-500/10 px-4 py-2 text-sm font-semibold text-brand-100 shadow-glow transition hover:border-brand-300 hover:bg-brand-500/20" href="<?= BASE_URL ?>/index.php?route=care-guide">Zur Wissenssammlung</a>
    </div>
    <div class="mt-8 grid gap-6 md:grid-cols-3">
        <?php foreach ($careHighlights as $article): ?>
            <article class="flex h-full flex-col rounded-3xl border border-white/5 bg-night-900/70 p-6 shadow-lg shadow-black/40 transition hover:border-brand-400/60 hover:shadow-glow">
                <h3 class="text-xl font-semibold text-white"><?= htmlspecialchars($article['title']) ?></h3>
                <?php if (!empty($article['summary'])): ?>
                    <p class="mt-3 text-sm text-slate-200"><?= nl2br(htmlspecialchars($article['summary'])) ?></p>
                <?php endif; ?>
                <a class="mt-auto inline-flex items-center gap-2 rounded-full border border-white/10 px-4 py-2 text-sm font-semibold text-slate-100 transition hover:border-brand-400 hover:text-brand-100" href="<?= BASE_URL ?>/index.php?route=care-article&amp;slug=<?= urlencode($article['slug']) ?>">Leitfaden öffnen</a>
            </article>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>

<section class="mx-auto mt-16 w-full max-w-7xl px-4 sm:px-6 lg:px-8">
    <h2 class="text-2xl font-semibold text-white sm:text-3xl">Vertrauen &amp; Transparenz</h2>
    <div class="mt-6 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
        <article class="rounded-3xl border border-white/5 bg-night-900/70 p-6 text-sm text-slate-200 shadow-lg shadow-black/30">
            <h3 class="text-lg font-semibold text-white">Impressum &amp; Rechtliches</h3>
            <p class="mt-2 leading-relaxed">Alle Pflichtangaben, Abgabebedingungen und aktuelle CITES-Hinweise für unsere Tiere.</p>
            <a class="mt-4 inline-flex items-center gap-2 text-brand-200 underline" href="<?= BASE_URL ?>/index.php?route=page&amp;slug=impressum">Zum Impressum</a>
        </article>
        <article class="rounded-3xl border border-white/5 bg-night-900/70 p-6 text-sm text-slate-200 shadow-lg shadow-black/30">
            <h3 class="text-lg font-semibold text-white">Datenschutz &amp; Kontakt</h3>
            <p class="mt-2 leading-relaxed">Transparente Datenschutzhinweise, Kontaktformular mit Spam-Schutz und direkte Ansprechpartner.</p>
            <a class="mt-4 inline-flex items-center gap-2 text-brand-200 underline" href="<?= BASE_URL ?>/index.php?route=page&amp;slug=datenschutz">Datenschutz lesen</a>
        </article>
        <article class="rounded-3xl border border-white/5 bg-night-900/70 p-6 text-sm text-slate-200 shadow-lg shadow-black/30">
            <h3 class="text-lg font-semibold text-white">Partner &amp; Referenzen</h3>
            <p class="mt-2 leading-relaxed">Zusammenarbeit mit Tierärzten, Verbänden und anerkannten Reptilien-Communities.</p>
            <a class="mt-4 inline-flex items-center gap-2 text-brand-200 underline" href="<?= BASE_URL ?>/index.php?route=page&amp;slug=partner">Partner entdecken</a>
        </article>
    </div>
</section>

<?php include __DIR__ . '/partials/footer.php'; ?>
