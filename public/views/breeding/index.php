<?php include __DIR__ . '/../partials/header.php'; ?>
<h1>Zuchtplanung</h1>
<p class="text-muted">Interne Übersicht über geplante Verpaarungen und Inkubationsschritte.</p>
<div class="grid" style="gap:2rem;margin-top:2rem;">
    <?php foreach ($breedingPlans as $plan): ?>
        <article class="card">
            <header style="display:flex;justify-content:space-between;align-items:center;gap:1rem;">
                <div>
                    <h2><?= htmlspecialchars($plan['title']) ?></h2>
                    <p class="text-muted">Saison: <?= htmlspecialchars($plan['season'] ?: 'offen') ?></p>
                </div>
            </header>
            <?php if (!empty($plan['expected_genetics'])): ?>
                <div class="plan-entry__section">
                    <strong>Erwartete Genetik:</strong>
                    <div class="rich-text-content"><?= render_rich_text($plan['expected_genetics']) ?></div>
                </div>
            <?php endif; ?>
            <?php if (!empty($plan['incubation_notes'])): ?>
                <div class="plan-entry__section">
                    <strong>Inkubationsnotizen:</strong>
                    <div class="rich-text-content"><?= render_rich_text($plan['incubation_notes']) ?></div>
                </div>
            <?php endif; ?>
            <?php if (!empty($plan['notes'])): ?>
                <div class="plan-entry__section">
                    <strong>Notizen:</strong>
                    <div class="rich-text-content"><?= render_rich_text($plan['notes']) ?></div>
                </div>
            <?php endif; ?>
            <h3>Elterntiere</h3>
            <?php if (empty($plan['parents'])): ?>
                <p class="text-muted">Noch keine Eltern hinterlegt.</p>
            <?php else: ?>
                <ul class="plan-parents">
                    <?php foreach ($plan['parents'] as $parent): ?>
                        <li>
                            <div class="plan-parent__title">
                                <strong><?= htmlspecialchars($parent['parent_type'] === 'virtual' ? ($parent['name'] ?: 'Virtuell') : ($parent['animal_name'] ?? $parent['name'] ?? 'Unbenannt')) ?></strong>
                                <?php if ($parent['sex']): ?>
                                    <span class="badge"><?= htmlspecialchars(strtoupper($parent['sex'])) ?></span>
                                <?php endif; ?>
                            </div>
                            <div class="text-muted">
                                <?= htmlspecialchars($parent['parent_type'] === 'virtual' ? 'Virtuelles Tier' : 'Bestandstier') ?>
                            </div>
                            <?php if ($parent['parent_type'] === 'animal' && $parent['animal_species']): ?>
                                <div><?= htmlspecialchars($parent['animal_species']) ?></div>
                            <?php elseif (!empty($parent['species'])): ?>
                                <div><?= htmlspecialchars($parent['species']) ?></div>
                            <?php endif; ?>
                            <?php if (!empty($parent['animal_genetics']) || !empty($parent['genetics'])): ?>
                                <div><strong>Genetik:</strong> <?= htmlspecialchars($parent['animal_genetics'] ?? $parent['genetics']) ?></div>
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
<?php include __DIR__ . '/../partials/footer.php'; ?>

