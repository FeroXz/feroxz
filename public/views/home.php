<section class="card">
    <h2>Aktuelle Beiträge</h2>
    <div class="grid">
        <?php foreach ($posts as $post): ?>
            <article class="card">
                <h3><a href="<?= url('post', ['slug' => $post['slug']]) ?>"><?= htmlspecialchars($post['title']) ?></a></h3>
                <p><?= nl2br(htmlspecialchars($post['excerpt'])) ?></p>
                <small>Veröffentlicht am <?= date('d.m.Y', strtotime($post['published_at'])) ?></small>
            </article>
        <?php endforeach; ?>
    </div>
</section>

<section class="card">
    <h2>Galerie-Highlights</h2>
    <div class="gallery-grid">
        <?php foreach ($gallery as $item): ?>
            <div class="gallery-item">
                <?php if ($item['image_path']): ?>
                    <img src="<?= htmlspecialchars($item['image_path']) ?>" alt="<?= htmlspecialchars($item['title']) ?>">
                <?php endif; ?>
                <div class="content">
                    <h3><?= htmlspecialchars($item['title']) ?></h3>
                    <p><?= nl2br(htmlspecialchars($item['description'])) ?></p>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<section class="card">
    <h2>Genetik</h2>
    <p>Erkunde unsere Datenbank für <em>Pogona vitticeps</em> und <em>Heterodon nasicus</em> oder starte direkt den Genetik-Rechner.</p>
    <a class="button" href="<?= url('genetics') ?>">Genetik entdecken</a>
    <a class="button secondary" href="<?= url('genetics/calculator') ?>">Genetik-Rechner</a>
</section>
