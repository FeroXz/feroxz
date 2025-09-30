<section class="card">
    <h2>Tierübersicht</h2>
    <p>Entdecke eine Auswahl deiner eingetragenen Tiere. Die Übersicht zeigt Bilder, Genetik und besondere Notizen der im Adminbereich markierten Tiere.</p>
</section>

<?php if (empty($animals)): ?>
    <section class="card">
        <p>Aktuell sind keine Tiere für die Übersicht ausgewählt. Du kannst Tiere im Adminbereich markieren, um sie hier anzuzeigen.</p>
    </section>
<?php else: ?>
    <section class="showcase-grid">
        <?php foreach ($animals as $animal): ?>
            <?php $genes = $genesBySpecies[$animal['species_id']] ?? []; ?>
            <?php $genotypeMap = buildAnimalGenotypeMap($genes, $animal['genotypes'] ?? []); ?>
            <?php $primaryImage = $animal['images'][0]['image_path'] ?? null; ?>
            <?php
                $initial = strtoupper(substr($animal['name'], 0, 1));
                if (function_exists('mb_substr')) {
                    $initial = mb_strtoupper(mb_substr($animal['name'], 0, 1, 'UTF-8'), 'UTF-8');
                }
            ?>
            <article class="showcase-card">
                <figure class="showcase-card__media">
                    <?php if ($primaryImage): ?>
                        <img src="<?= htmlspecialchars($primaryImage) ?>" alt="<?= htmlspecialchars($animal['name']) ?>" loading="lazy">
                    <?php else: ?>
                        <div class="showcase-card__placeholder" aria-hidden="true">
                            <span><?= htmlspecialchars($initial) ?></span>
                        </div>
                    <?php endif; ?>
                </figure>
                <div class="showcase-card__body">
                    <h3><?= htmlspecialchars($animal['name']) ?></h3>
                    <p class="showcase-card__species"><?= htmlspecialchars($animal['common_name']) ?> <span>(<?= htmlspecialchars($animal['latin_name']) ?>)</span></p>
                    <ul class="showcase-card__facts">
                        <?php if (!empty($animal['age'])): ?>
                            <li><strong>Alter:</strong> <?= htmlspecialchars($animal['age']) ?></li>
                        <?php endif; ?>
                        <?php if (!empty($animal['origin'])): ?>
                            <li><strong>Herkunft:</strong> <?= htmlspecialchars($animal['origin']) ?></li>
                        <?php endif; ?>
                        <li><strong>Genetik:</strong> <?= htmlspecialchars(summarizeGeneStates($genes, $genotypeMap)) ?></li>
                        <?php if (!empty($animal['genetics_notes'])): ?>
                            <li><strong>Genetik-Notizen:</strong> <?= nl2br(htmlspecialchars($animal['genetics_notes'])) ?></li>
                        <?php endif; ?>
                        <?php if (!empty($animal['special_notes'])): ?>
                            <li><strong>Besonderheiten:</strong> <?= nl2br(htmlspecialchars($animal['special_notes'])) ?></li>
                        <?php endif; ?>
                    </ul>
                </div>
            </article>
        <?php endforeach; ?>
    </section>
<?php endif; ?>
