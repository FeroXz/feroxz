<?php include __DIR__ . '/../partials/header.php'; ?>
<article class="content-card">
    <h1><?= htmlspecialchars($gene['name']) ?> <small class="text-muted"><?= htmlspecialchars($species['name']) ?></small></h1>
    <p class="text-muted">Vererbung: <?= htmlspecialchars(ucfirst($gene['inheritance'])) ?></p>
    <div class="rich-text">
        <p><?= nl2br(htmlspecialchars($gene['description'])) ?></p>
        <ul class="gene-legend">
            <?php if (!empty($gene['visual_label'])): ?><li><strong>Visual:</strong> <?= htmlspecialchars($gene['visual_label']) ?></li><?php endif; ?>
            <?php if (!empty($gene['heterozygous_label'])): ?><li><strong>Het:</strong> <?= htmlspecialchars($gene['heterozygous_label']) ?></li><?php endif; ?>
            <?php if (!empty($gene['homozygous_label'])): ?><li><strong>Super:</strong> <?= htmlspecialchars($gene['homozygous_label']) ?></li><?php endif; ?>
            <li><strong>Wildtyp:</strong> <?= htmlspecialchars($gene['wild_label'] ?? 'Wildtyp') ?></li>
        </ul>
    </div>
    <div style="margin-top:2rem;display:flex;gap:1rem;flex-wrap:wrap;">
        <a class="btn" href="<?= route_url('genetics/calculator', ['species' => $species['slug']]) ?>">Rechner mit <?= htmlspecialchars($gene['name']) ?></a>
        <a class="btn btn-secondary" href="<?= route_url('genetics/species', ['slug' => $species['slug']]) ?>">Zurück zur Übersicht</a>
    </div>
</article>
<?php include __DIR__ . '/../partials/footer.php'; ?>
