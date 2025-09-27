<section class="genetics calculator">
  <header class="page-header">
    <h1>Genetik-Rechner – <?= htmlspecialchars($species['name'], ENT_QUOTES, 'UTF-8') ?></h1>
    <?php if (!empty($species['scientific_name'])): ?>
    <p class="scientific"><?= htmlspecialchars($species['scientific_name'], ENT_QUOTES, 'UTF-8') ?></p>
    <?php endif; ?>
    <div class="actions">
      <a class="button secondary" href="/genetics/<?= htmlspecialchars($species['slug'], ENT_QUOTES, 'UTF-8') ?>">Zurück zur Art</a>
      <a class="button" href="/genetics">Weitere Arten</a>
    </div>
  </header>

  <?php if (empty($genes)): ?>
  <p class="empty">Für diese Art stehen derzeit keine Gene zur Auswahl.</p>
  <?php else: ?>
  <form method="post" class="calculator-form">
    <table class="table">
      <thead>
        <tr>
          <th>Gen</th>
          <th>Vererbung</th>
          <th>Sire</th>
          <th>Dam</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($genes as $gene): ?>
        <tr>
          <td><?= htmlspecialchars($gene['name'], ENT_QUOTES, 'UTF-8') ?></td>
          <td><?= htmlspecialchars($inheritanceLabels[$gene['inheritance_type']] ?? $gene['inheritance_type'], ENT_QUOTES, 'UTF-8') ?></td>
          <td>
            <select name="sire[<?= (int) $gene['id'] ?>]">
              <option value="0" <?= $sireSelection[$gene['id']] === 0 ? 'selected' : '' ?>>
                <?= htmlspecialchars($gene['normal_label'], ENT_QUOTES, 'UTF-8') ?>
              </option>
              <option value="1" <?= $sireSelection[$gene['id']] === 1 ? 'selected' : '' ?>>
                <?= htmlspecialchars($gene['heterozygous_label'], ENT_QUOTES, 'UTF-8') ?>
              </option>
              <option value="2" <?= $sireSelection[$gene['id']] === 2 ? 'selected' : '' ?>>
                <?= htmlspecialchars($gene['homozygous_label'], ENT_QUOTES, 'UTF-8') ?>
              </option>
            </select>
          </td>
          <td>
            <select name="dam[<?= (int) $gene['id'] ?>]">
              <option value="0" <?= $damSelection[$gene['id']] === 0 ? 'selected' : '' ?>>
                <?= htmlspecialchars($gene['normal_label'], ENT_QUOTES, 'UTF-8') ?>
              </option>
              <option value="1" <?= $damSelection[$gene['id']] === 1 ? 'selected' : '' ?>>
                <?= htmlspecialchars($gene['heterozygous_label'], ENT_QUOTES, 'UTF-8') ?>
              </option>
              <option value="2" <?= $damSelection[$gene['id']] === 2 ? 'selected' : '' ?>>
                <?= htmlspecialchars($gene['homozygous_label'], ENT_QUOTES, 'UTF-8') ?>
              </option>
            </select>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

    <div class="form-actions">
      <button class="button" type="submit">Kreuzung berechnen</button>
    </div>
  </form>

  <?php if (!empty($results)): ?>
  <section class="results">
    <h2>Ergebnisse</h2>

    <div class="result-columns">
      <?php foreach ($results['genes'] as $geneResult): ?>
      <article class="card">
        <h3><?= htmlspecialchars($geneResult['gene']['name'], ENT_QUOTES, 'UTF-8') ?></h3>
        <p class="inheritance">
          Vererbung:
          <?= htmlspecialchars($inheritanceLabels[$geneResult['gene']['inheritance_type']] ?? $geneResult['gene']['inheritance_type'], ENT_QUOTES, 'UTF-8') ?>
        </p>
        <table class="table compact">
          <thead>
            <tr>
              <th>Genotyp</th>
              <th>Wahrscheinlichkeit</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($geneResult['distribution'] as $state): ?>
            <tr>
              <td><?= htmlspecialchars($state['label'], ENT_QUOTES, 'UTF-8') ?></td>
              <td><?= htmlspecialchars(formatProbability($state['probability']), ENT_QUOTES, 'UTF-8') ?></td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </article>
      <?php endforeach; ?>
    </div>

    <article class="card">
      <h3>Alle Genkombinationen</h3>
      <?php if (empty($results['combinations'])): ?>
      <p class="empty">Keine gültigen Kombinationen berechnet.</p>
      <?php else: ?>
      <table class="table">
        <thead>
          <tr>
            <th>Kombination</th>
            <th>Wahrscheinlichkeit</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($results['combinations'] as $combo): ?>
          <tr>
            <td>
              <?php foreach ($combo['labels'] as $geneName => $label): ?>
              <span class="pill">
                <strong><?= htmlspecialchars($geneName, ENT_QUOTES, 'UTF-8') ?>:</strong>
                <?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?>
              </span>
              <?php endforeach; ?>
            </td>
            <td><?= htmlspecialchars(formatProbability($combo['probability']), ENT_QUOTES, 'UTF-8') ?></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
      <?php endif; ?>
    </article>
  </section>
  <?php endif; ?>
  <?php endif; ?>
</section>
