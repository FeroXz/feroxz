<?php include __DIR__ . '/../partials/header.php'; ?>
<section class="section">
    <div class="section__inner">
        <header class="section-header">
            <h1 class="section-header__title">Zuchtplanung</h1>
            <p class="section-header__description">Interne Übersicht über geplante Verpaarungen, erwartete Morphs und Inkubationsschritte.</p>
        </header>
        <div class="highlight-deck">
            <?php foreach ($breedingPlans as $plan): ?>
                <article class="card card--neutral">
                    <header>
                        <h2 class="card__title"><?= htmlspecialchars($plan['title']) ?></h2>
                        <p class="card__subtitle">Saison: <?= htmlspecialchars($plan['season'] ?: 'offen') ?></p>
                    </header>
                    <?php if (!empty($plan['expected_genetics'])): ?>
                        <div class="rich-text-content">
                            <h3>Erwartete Genetik</h3>
                            <?= render_rich_text($plan['expected_genetics']) ?>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($plan['incubation_notes'])): ?>
                        <div class="rich-text-content">
                            <h3>Inkubationsnotizen</h3>
                            <?= render_rich_text($plan['incubation_notes']) ?>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($plan['notes'])): ?>
                        <div class="rich-text-content">
                            <h3>Notizen</h3>
                            <?= render_rich_text($plan['notes']) ?>
                        </div>
                    <?php endif; ?>
                    <h3>Elterntiere</h3>
                    <?php if (empty($plan['parents'])): ?>
                        <p class="card__subtitle">Noch keine Eltern hinterlegt.</p>
                    <?php else: ?>
                        <ul class="listing-card__meta">
                            <?php foreach ($plan['parents'] as $parent): ?>
                                <li>
                                    <strong><?= htmlspecialchars($parent['parent_type'] === 'virtual' ? ($parent['name'] ?: 'Virtuell') : ($parent['animal_name'] ?? $parent['name'] ?? 'Unbenannt')) ?></strong>
                                    <?php if ($parent['sex']): ?>
                                        – <?= htmlspecialchars(strtoupper($parent['sex'])) ?>
                                    <?php endif; ?>
                                    <div>
                                        <small><?= htmlspecialchars($parent['parent_type'] === 'virtual' ? 'Virtuelles Tier' : 'Bestandstier') ?></small>
                                    </div>
                                    <?php if ($parent['parent_type'] === 'animal' && $parent['animal_species']): ?>
                                        <div><?= htmlspecialchars($parent['animal_species']) ?></div>
                                    <?php elseif (!empty($parent['species'])): ?>
                                        <div><?= htmlspecialchars($parent['species']) ?></div>
                                    <?php endif; ?>
                                    <?php if (!empty($parent['animal_genetics']) || !empty($parent['genetics'])): ?>
                                        <div>Genetik: <?= htmlspecialchars($parent['animal_genetics'] ?? $parent['genetics']) ?></div>
                                    <?php endif; ?>
                                    <?php if (!empty($parent['notes'])): ?>
                                        <div><?= nl2br(htmlspecialchars($parent['notes'])) ?></div>
                                    <?php endif; ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php include __DIR__ . '/../partials/footer.php'; ?>
