<?php include __DIR__ . '/../partials/header.php'; ?>
<article class="content-card">
    <h1><?= htmlspecialchars($guide['headline']) ?></h1>
    <p class="text-muted"><?= htmlspecialchars($guide['summary']) ?></p>
    <div class="guide-grid">
        <section>
            <h2>Habitat</h2>
            <p><?= nl2br(htmlspecialchars($guide['habitat'])) ?></p>
        </section>
        <section>
            <h2>Beleuchtung & Klima</h2>
            <p><?= nl2br(htmlspecialchars($guide['lighting'])) ?></p>
        </section>
        <section>
            <h2>Ernährung</h2>
            <p><?= nl2br(htmlspecialchars($guide['diet'])) ?></p>
        </section>
        <section>
            <h2>Enrichment</h2>
            <p><?= nl2br(htmlspecialchars($guide['enrichment'])) ?></p>
        </section>
        <section>
            <h2>Gesundheit</h2>
            <p><?= nl2br(htmlspecialchars($guide['health'])) ?></p>
        </section>
        <section>
            <h2>Zucht</h2>
            <p><?= nl2br(htmlspecialchars($guide['breeding'])) ?></p>
        </section>
    </div>
</article>
<?php include __DIR__ . '/../partials/footer.php'; ?>
