<section class="card account-hero">
    <div class="account-hero__content">
        <h2>Deine Tierkollektion</h2>
        <p>Hier findest du alle Tiere, die deinem Benutzerkonto zugeordnet sind – inklusive Genetik, Herkunft und besonderer Notizen. Private Tiere sind nur für dich sichtbar und tauchen weder in der öffentlichen Tierübersicht noch im Genetik-Teiler anderer Nutzer auf.</p>
    </div>
    <?php if (userHasPermission($currentUser, 'animals') || ($currentUser['role'] ?? '') === 'admin'): ?>
        <div class="account-hero__cta">
            <a class="button" href="<?= url('admin/animals') ?>">Tierbestand bearbeiten</a>
        </div>
    <?php endif; ?>
</section>

<?php if (empty($animals)): ?>
    <section class="card empty-state">
        <h3>Noch keine Tiere hinterlegt</h3>
        <p>Erfasse deine ersten Tiere im Adminbereich, damit sie hier erscheinen und für den Genetik-Rechner verfügbar sind.</p>
        <?php if (userHasPermission($currentUser, 'animals') || ($currentUser['role'] ?? '') === 'admin'): ?>
            <a class="button" href="<?= url('admin/animals') ?>">Jetzt Tier anlegen</a>
        <?php endif; ?>
    </section>
<?php else: ?>
    <section class="card account-animals">
        <div class="account-animals__grid">
            <?php foreach ($animals as $animal): ?>
                <?php $genes = $genesBySpecies[$animal['species_id']] ?? []; ?>
                <?php $genotypeMap = $animal['genotype_map'] ?? buildAnimalGenotypeMap($genes, $animal['genotypes'] ?? []); ?>
                <?php $primaryImage = $animal['primary_image'] ?? ($animal['images'][0]['image_path'] ?? null); ?>
                <?php
                    $initial = strtoupper(substr($animal['name'], 0, 1));
                    if (function_exists('mb_substr')) {
                        $initial = mb_strtoupper(mb_substr($animal['name'], 0, 1, 'UTF-8'), 'UTF-8');
                    }
                ?>
                <article class="animal-card">
                    <div class="animal-card__media">
                        <?php if ($primaryImage): ?>
                            <img src="<?= htmlspecialchars($primaryImage) ?>" alt="<?= htmlspecialchars($animal['name']) ?>" loading="lazy">
                        <?php else: ?>
                            <span class="animal-card__placeholder" aria-hidden="true"><?= htmlspecialchars($initial) ?></span>
                        <?php endif; ?>
                        <div class="animal-card__badges">
                            <?php if (!empty($animal['is_private'])): ?>
                                <span class="badge warning">Privat</span>
                            <?php else: ?>
                                <span class="badge info">Intern</span>
                                <?php if (!empty($animal['is_showcased'])): ?>
                                    <span class="badge success">Showcase</span>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="animal-card__body">
                        <header>
                            <h3><?= htmlspecialchars($animal['name']) ?></h3>
                            <p class="animal-card__species"><?= htmlspecialchars($animal['common_name'] ?? '') ?> <span>(<?= htmlspecialchars($animal['latin_name'] ?? '') ?>)</span></p>
                        </header>
                        <dl class="animal-card__meta">
                            <?php if (!empty($animal['age'])): ?>
                                <div>
                                    <dt>Alter</dt>
                                    <dd><?= htmlspecialchars($animal['age']) ?></dd>
                                </div>
                            <?php endif; ?>
                            <?php if (!empty($animal['origin'])): ?>
                                <div>
                                    <dt>Herkunft</dt>
                                    <dd><?= htmlspecialchars($animal['origin']) ?></dd>
                                </div>
                            <?php endif; ?>
                            <div>
                                <dt>Genetik</dt>
                                <dd><?= htmlspecialchars($animal['gene_summary'] ?? summarizeGeneStates($genes, $genotypeMap)) ?></dd>
                            </div>
                            <?php if (!empty($animal['genetics_notes'])): ?>
                                <div>
                                    <dt>Genetik-Notizen</dt>
                                    <dd><?= nl2br(htmlspecialchars($animal['genetics_notes'])) ?></dd>
                                </div>
                            <?php endif; ?>
                            <?php if (!empty($animal['special_notes'])): ?>
                                <div>
                                    <dt>Besonderheiten</dt>
                                    <dd><?= nl2br(htmlspecialchars($animal['special_notes'])) ?></dd>
                                </div>
                            <?php endif; ?>
                            <div>
                                <dt>Zuletzt aktualisiert</dt>
                                <dd><?= htmlspecialchars(date('d.m.Y', strtotime($animal['updated_at'] ?? $animal['created_at'] ?? 'now'))) ?></dd>
                            </div>
                        </dl>
                        <?php if (!empty($animal['images']) && count($animal['images']) > 1): ?>
                            <div class="animal-card__gallery">
                                <?php foreach (array_slice($animal['images'], 1, 3) as $image): ?>
                                    <span class="thumbnail" style="background-image: url('<?= htmlspecialchars($image['image_path']) ?>');" aria-hidden="true"></span>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                        <?php if (userHasPermission($currentUser, 'animals') || ($currentUser['role'] ?? '') === 'admin'): ?>
                            <div class="animal-card__actions">
                                <a class="button secondary" href="<?= url('admin/animals', ['id' => $animal['id']]) ?>">Im Admin öffnen</a>
                            </div>
                        <?php endif; ?>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </section>
<?php endif; ?>
