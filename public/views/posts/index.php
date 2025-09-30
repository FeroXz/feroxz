<?php include __DIR__ . '/../partials/header.php'; ?>
<h1>Journal</h1>
<p class="text-muted">Insights, Projekt-Updates und Praxiswissen rund um Bartagamen und Hakennasennattern.</p>
<div class="grid cards" style="margin-top:2rem;">
    <?php foreach ($posts as $post): ?>
        <article class="card">
            <h3><?= htmlspecialchars($post['title']) ?></h3>
            <?php if (!empty($post['excerpt'])): ?>
                <p class="text-muted"><?= htmlspecialchars($post['excerpt']) ?></p>
            <?php endif; ?>
            <a class="btn btn-secondary" href="<?= route_url('post', ['slug' => $post['slug']]) ?>">Beitrag lesen</a>
        </article>
    <?php endforeach; ?>
</div>
<?php include __DIR__ . '/../partials/footer.php'; ?>
