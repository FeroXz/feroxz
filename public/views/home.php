<?php $title = 'Start'; ?>
<section class="page-hero home-hero">
    <div class="page-hero__content">
        <span class="eyebrow">Feroxz CMS</span>
        <h1><?= htmlspecialchars(settingValue($settings, 'home_hero_title', 'Reptilien nachhaltig züchten & vermitteln')) ?></h1>
        <p><?= nl2br(htmlspecialchars(settingValue($settings, 'home_hero_subtitle', 'Pflegeleitfäden, Genetik-Tools und ein moderner Tierbestand – alles in einem CMS.'))) ?></p>
        <div class="page-hero__actions">
            <a class="button" href="<?= url('genetics/calculator') ?>">Genetik-Rechner öffnen</a>
            <a class="button ghost" href="<?= url('adoption') ?>">Tierabgabe ansehen</a>
        </div>
    </div>
</section>

<section class="panel">
    <header class="panel__header">
        <div>
            <h2>Aktuelle Beiträge</h2>
            <p>Neuigkeiten, Projektupdates und Pflegetipps aus deinem Bestand.</p>
        </div>
        <a class="button subtle" href="<?= url('page', ['slug' => 'pflegeleitfaden']) ?>">Pflegeleitfaden öffnen</a>
    </header>
    <div class="card-grid">
        <?php foreach ($posts as $post): ?>
            <article class="info-card">
                <span class="info-card__eyebrow">Veröffentlicht am <?= date('d.m.Y', strtotime($post['published_at'])) ?></span>
                <h3><a href="<?= url('post', ['slug' => $post['slug']]) ?>"><?= htmlspecialchars($post['title']) ?></a></h3>
                <p><?= nl2br(htmlspecialchars($post['excerpt'])) ?></p>
                <a class="button link" href="<?= url('post', ['slug' => $post['slug']]) ?>">Weiterlesen</a>
            </article>
        <?php endforeach; ?>
    </div>
</section>

<section class="panel">
    <header class="panel__header">
        <div>
            <h2>Galerie-Highlights</h2>
            <p>Ausgewählte Impressionen aus Zucht und Haltung.</p>
        </div>
        <a class="button subtle" href="<?= url('gallery') ?>">Zur Galerie</a>
    </header>
    <div class="gallery-grid">
        <?php foreach ($gallery as $item): ?>
            <article class="gallery-card">
                <?php if ($item['image_path']): ?>
                    <img src="<?= htmlspecialchars($item['image_path']) ?>" alt="<?= htmlspecialchars($item['title']) ?>">
                <?php endif; ?>
                <div class="gallery-card__body">
                    <h3><?= htmlspecialchars($item['title']) ?></h3>
                    <p><?= nl2br(htmlspecialchars($item['description'])) ?></p>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
</section>

<section class="panel highlight">
    <div class="highlight__content">
        <h2>Genetik-Werkzeuge</h2>
        <p>Berechne Punnett-Wahrscheinlichkeiten wie bei MorphMarket, pflege eine detaillierte Gen-Datenbank und dokumentiere Tiere inklusive Genotypen.</p>
        <div class="highlight__actions">
            <a class="button" href="<?= url('genetics') ?>">Genetik entdecken</a>
            <a class="button ghost" href="<?= url('genetics/calculator') ?>">Rechner starten</a>
        </div>
    </div>
</section>
