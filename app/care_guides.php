<?php

function ensure_default_care_guides(PDO $pdo): void
{
    $count = (int)$pdo->query('SELECT COUNT(*) FROM care_guides')->fetchColumn();
    if ($count > 0) {
        return;
    }

    $guides = [
        [
            'species' => 'Bartagame',
            'slug' => 'pogona-vitticeps',
            'headline' => 'Pogona vitticeps – Pflegeleitfaden',
            'summary' => 'Ausführliche Haltungsrichtlinien für mittelgroße Wüstenbewohner mit hohem UV-Bedarf.',
            'habitat' => 'Terrarium ab 150 × 80 × 80 cm (L × B × H) mit sandigem Lehmsubstrat, strukturierenden Kletterästen und Höhlen. Temperaturgradient von 28–32 °C, Sonnenplatz 45–50 °C, Nachtabsenkung auf 20–22 °C.',
            'lighting' => 'Metallhalogenidlampe oder Kombination aus HQI und T5-HO UVB (12 %). Beleuchtungsdauer 12–13 Stunden. UVB-Röhren alle 12 Monate wechseln, Spots nach 6 Monaten Leistung prüfen.',
            'diet' => 'Abwechslungsreiche Mischung aus Wildkräutern (Löwenzahn, Wegerich), Salaten mit niedrigem Oxalsäuregehalt (Endivie, Römersalat) und saisonalem Gemüse. Proteinquellen: Heuschrecken, Schaben, Black Soldier Fly Larven. Jungtiere: 70 % Insekten, Adulttiere: 70 % Pflanzen. Supplemente: Calcium ohne D3 bei jeder Fütterung, mit D3 wöchentlich, Multivitamin alle 10–14 Tage.',
            'enrichment' => 'Strukturelle Anpassungen, wechselnde Sonnenplätze, Duftspuren sowie Fütterung aus Futterkugeln oder mittels Target-Feeding halten die Tiere mental aktiv.',
            'health' => 'Regelmäßige Kotproben, Gewichtskontrolle, Beobachtung von Häutungsproblemen. Anzeichen für metabolische Knochenerkrankung (weiche Kiefer, Tremor) umgehend tierärztlich abklären.',
            'breeding' => 'Brumation von 8–10 Wochen bei 16–18 °C zur Stimulation der Fortpflanzung. Eiablagebox mit feuchtem Sand-Lehm-Gemisch bereitstellen. Inkubation bei 30–31 °C, Schlupf nach 60 Tagen.'
        ],
        [
            'species' => 'Hakennasennatter',
            'slug' => 'heterodon-nasicus',
            'headline' => 'Heterodon nasicus – Pflegeleitfaden',
            'summary' => 'Detaillierte Empfehlungen für nordamerikanische Bodenbewohner mit variablem Temperaturprofil.',
            'habitat' => 'Terrarium ab 90 × 45 × 45 cm mit grabfähigem Substrat (Sand-Erde-Mix), mehreren Verstecken und leichter Steigung für Temperaturzonen. Bodentemperatur tagsüber 26–28 °C, Sonnenplatz 32–34 °C, Nachtabsenkung auf 20–22 °C.',
            'lighting' => 'LED-Taglicht zur Tagesrhythmik und optional UVB-Komponenten (T5 6 %). Beleuchtung 12 Stunden. Wärmematten nur mit Thermostat und Schutzgitter verwenden.',
            'diet' => 'Präparierte Frostmäuse entsprechend Körperumfang, gelegentlich Frostküken. Jungtiere alle 5 Tage, adulte Tiere alle 7–10 Tage füttern. Nahrung mit Reptilienvitaminen stäuben, besonders bei wachstumsintensiven Phasen.',
            'enrichment' => 'Wechselnde Bodensubstrate, Borkentunnel, Duftspuren (z. B. Anis, Fisch) zur Futtersuchmotivation. Regelmäßiges Handling in kurzen Sessions zur Sozialisierung.',
            'health' => 'Achte auf Schuppenverletzungen nach der Häutung, Atemgeräusche und Futtermüdigkeit. Bei anhaltender Futterverweigerung (>4 Wochen) oder Nasenbluten tierärztliche Kontrolle veranlassen.',
            'breeding' => 'Winterruhe 8 Wochen bei 10–12 °C, danach langsame Temperatursteigerung. Paarung bei 24–26 °C möglich. Eiablagebox mit feuchtem Vermiculit, Inkubation bei 28 °C, Schlupf nach 50–55 Tagen.'
        ],
    ];

    $stmt = $pdo->prepare('INSERT INTO care_guides(species, slug, headline, summary, habitat, lighting, diet, enrichment, health, breeding) VALUES (:species, :slug, :headline, :summary, :habitat, :lighting, :diet, :enrichment, :health, :breeding)');
    foreach ($guides as $guide) {
        $stmt->execute($guide);
    }
}

function get_care_guides(PDO $pdo): array
{
    return $pdo->query('SELECT * FROM care_guides ORDER BY species ASC')->fetchAll();
}

function get_care_guide(PDO $pdo, int $id): ?array
{
    $stmt = $pdo->prepare('SELECT * FROM care_guides WHERE id = :id');
    $stmt->execute(['id' => $id]);
    $guide = $stmt->fetch();
    return $guide ?: null;
}

function get_care_guide_by_slug(PDO $pdo, string $slug): ?array
{
    $stmt = $pdo->prepare('SELECT * FROM care_guides WHERE slug = :slug');
    $stmt->execute(['slug' => $slug]);
    $guide = $stmt->fetch();
    return $guide ?: null;
}

function create_care_guide(PDO $pdo, array $data): void
{
    $stmt = $pdo->prepare('INSERT INTO care_guides(species, slug, headline, summary, habitat, lighting, diet, enrichment, health, breeding) VALUES (:species, :slug, :headline, :summary, :habitat, :lighting, :diet, :enrichment, :health, :breeding)');
    $stmt->execute([
        'species' => $data['species'],
        'slug' => $data['slug'],
        'headline' => $data['headline'],
        'summary' => $data['summary'] ?? null,
        'habitat' => $data['habitat'] ?? null,
        'lighting' => $data['lighting'] ?? null,
        'diet' => $data['diet'] ?? null,
        'enrichment' => $data['enrichment'] ?? null,
        'health' => $data['health'] ?? null,
        'breeding' => $data['breeding'] ?? null,
    ]);
}

function update_care_guide(PDO $pdo, int $id, array $data): void
{
    $stmt = $pdo->prepare('UPDATE care_guides SET species = :species, slug = :slug, headline = :headline, summary = :summary, habitat = :habitat, lighting = :lighting, diet = :diet, enrichment = :enrichment, health = :health, breeding = :breeding WHERE id = :id');
    $stmt->execute([
        'species' => $data['species'],
        'slug' => $data['slug'],
        'headline' => $data['headline'],
        'summary' => $data['summary'] ?? null,
        'habitat' => $data['habitat'] ?? null,
        'lighting' => $data['lighting'] ?? null,
        'diet' => $data['diet'] ?? null,
        'enrichment' => $data['enrichment'] ?? null,
        'health' => $data['health'] ?? null,
        'breeding' => $data['breeding'] ?? null,
        'id' => $id,
    ]);
}

function delete_care_guide(PDO $pdo, int $id): void
{
    $stmt = $pdo->prepare('DELETE FROM care_guides WHERE id = :id');
    $stmt->execute(['id' => $id]);
}

