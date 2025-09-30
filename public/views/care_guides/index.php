<?php include __DIR__ . '/../partials/header.php'; ?>
<h1>Pflegeleitfäden</h1>
<p class="text-muted">Detailwissen zu Habitat, Ernährung und Gesundheitsvorsorge für unsere Kernarten.</p>
<div class="grid cards" style="margin-top:2rem;">
    <?php foreach ($guides as $guide): ?>
        <article class="card">
            <h3><?= htmlspecialchars($guide['headline']) ?></h3>
            <p class="text-muted"><?= htmlspecialchars($guide['summary']) ?></p>
            <a class="btn" href="<?= route_url('care-guide', ['slug' => $guide['slug']]) ?>">Leitfaden öffnen</a>
        </article>
    <?php endforeach; ?>
</div>
<?php include __DIR__ . '/../partials/footer.php'; ?>
