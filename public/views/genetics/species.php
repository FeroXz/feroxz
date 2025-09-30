<?php include __DIR__ . '/../partials/header.php'; ?>
<h1><?= htmlspecialchars($species['name']) ?> – Genetik</h1>
<p class="text-muted"><em><?= htmlspecialchars($species['scientific_name']) ?></em></p>
<p><?= nl2br(htmlspecialchars($species['description'])) ?></p>
<div class="grid cards" style="margin-top:2rem;">
    <?php foreach ($genes as $gene): ?>
        <article class="card">
            <h3><?= htmlspecialchars($gene['name']) ?></h3>
            <p class="text-muted">Vererbung: <?= htmlspecialchars(ucfirst($gene['inheritance'])) ?></p>
            <p><?= nl2br(htmlspecialchars($gene['description'])) ?></p>
            <div class="tag-row">
                <?php if (!empty($gene['visual_label'])): ?><span class="badge">Visual: <?= htmlspecialchars($gene['visual_label']) ?></span><?php endif; ?>
                <?php if (!empty($gene['heterozygous_label'])): ?><span class="badge">Het: <?= htmlspecialchars($gene['heterozygous_label']) ?></span><?php endif; ?>
                <?php if (!empty($gene['homozygous_label'])): ?><span class="badge">Super: <?= htmlspecialchars($gene['homozygous_label']) ?></span><?php endif; ?>
            </div>
            <div style="margin-top:1rem;">
                <a class="btn btn-secondary" href="<?= route_url('genetics/gene', ['species' => $species['slug'], 'gene' => $gene['slug']]) ?>">Details</a>
            </div>
        </article>
    <?php endforeach; ?>
</div>
<?php include __DIR__ . '/../partials/footer.php'; ?>
