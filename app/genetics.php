<?php

const GENETIC_INHERITANCE_MODES = ['recessive', 'dominant', 'incomplete_dominant', 'polygenic', 'other'];
const GENETIC_CALCULATOR_MODES = ['recessive', 'dominant', 'incomplete_dominant'];

function default_genetics_catalog(): array
{
    return [
        'heterodon-nasicus' => [
            'species' => [
                'name' => 'Heterodon nasicus',
                'slug' => 'heterodon-nasicus',
                'scientific_name' => 'Heterodon nasicus',
                'description' => 'Die westliche Hakennasennatter (<em>Heterodon nasicus</em>) besticht durch eine große Bandbreite an rezessiven und inkomplett dominanten Linien. Die hinterlegten Daten kombinieren die Morphpedia-Grunddaten mit bestätigten Ergänzungen aus der Zuchtpraxis.',
            ],
            'genes' => [
                [
                    'name' => 'Albino',
                    'slug' => 'albino',
                    'inheritance_mode' => 'recessive',
                    'description' => 'Vererbungsmodus: rezessiv. Ursprung laut Morphpedia 1992, etablierter Standard in westlichen Linien.',
                    'normal_label' => 'Wildtyp',
                    'heterozygous_label' => 'het Albino',
                    'homozygous_label' => 'Albino',
                    'display_order' => 1,
                ],
                [
                    'name' => 'Arctic',
                    'slug' => 'arctic',
                    'inheritance_mode' => 'incomplete_dominant',
                    'description' => 'Vererbungsmodus: inkomplett dominant. Der Super-Status wird als „Super Arctic“ geführt.',
                    'normal_label' => 'Wildtyp',
                    'heterozygous_label' => 'Arctic',
                    'homozygous_label' => 'Super Arctic',
                    'display_order' => 2,
                ],
                [
                    'name' => 'Axanthic',
                    'slug' => 'axanthic',
                    'inheritance_mode' => 'recessive',
                    'description' => 'Vererbungsmodus: rezessiv. Entfernt gelbe Pigmente und schafft graue bis silberne Tiere.',
                    'normal_label' => 'Wildtyp',
                    'heterozygous_label' => 'het Axanthic',
                    'homozygous_label' => 'Axanthic',
                    'display_order' => 3,
                ],
                [
                    'name' => 'Caramel',
                    'slug' => 'caramel',
                    'inheritance_mode' => 'recessive',
                    'description' => 'Vererbungsmodus: rezessiv. Bildet warme Karamelltöne und reduziert dunkle Kontraste.',
                    'normal_label' => 'Wildtyp',
                    'heterozygous_label' => 'het Caramel',
                    'homozygous_label' => 'Caramel',
                    'display_order' => 4,
                ],
                [
                    'name' => 'Conda',
                    'slug' => 'conda',
                    'inheritance_mode' => 'incomplete_dominant',
                    'description' => 'Vererbungsmodus: inkomplett dominant. Homozygote Tiere werden als „Super Conda“ geführt.',
                    'normal_label' => 'Wildtyp',
                    'heterozygous_label' => 'Conda',
                    'homozygous_label' => 'Super Conda',
                    'display_order' => 5,
                ],
                [
                    'name' => 'Evans Hypo',
                    'slug' => 'evans-hypo',
                    'inheritance_mode' => 'recessive',
                    'description' => 'Vererbungsmodus: rezessiv. Reduziert schwarze Pigmente und betont Pastelltöne.',
                    'normal_label' => 'Wildtyp',
                    'heterozygous_label' => 'het Evans Hypo',
                    'homozygous_label' => 'Evans Hypo',
                    'display_order' => 6,
                ],
                [
                    'name' => 'Extreme Red Albino',
                    'slug' => 'extreme-red-albino',
                    'inheritance_mode' => 'recessive',
                    'description' => 'Vererbungsmodus: rezessiv. Verstärkt rötliche Pigmente im Albino-Spektrum.',
                    'normal_label' => 'Wildtyp',
                    'heterozygous_label' => 'het Extreme Red Albino',
                    'homozygous_label' => 'Extreme Red Albino',
                    'display_order' => 7,
                ],
                [
                    'name' => 'Green Hypo',
                    'slug' => 'green-hypo',
                    'inheritance_mode' => 'polygenic',
                    'description' => 'Vererbungsmodus: polygen. Selektion auf entsättigte Grüntöne; wirkt additiv mit weiteren Linien.',
                    'normal_label' => 'Linienzucht',
                    'heterozygous_label' => 'Green Hypo',
                    'homozygous_label' => 'Green Hypo',
                    'display_order' => 8,
                ],
                [
                    'name' => 'Lavender',
                    'slug' => 'lavender',
                    'inheritance_mode' => 'recessive',
                    'description' => 'Vererbungsmodus: rezessiv. Pastellige Lavendeltöne durch reduzierte Melaninbildung.',
                    'normal_label' => 'Wildtyp',
                    'heterozygous_label' => 'het Lavender',
                    'homozygous_label' => 'Lavender',
                    'display_order' => 9,
                ],
                [
                    'name' => 'Lemon Hypo',
                    'slug' => 'lemon-hypo',
                    'inheritance_mode' => 'polygenic',
                    'description' => 'Vererbungsmodus: polygen. Selektiert leuchtend gelbe Tiere mit reduzierter Melaninlage.',
                    'normal_label' => 'Linienzucht',
                    'heterozygous_label' => 'Lemon Hypo',
                    'homozygous_label' => 'Lemon Hypo',
                    'display_order' => 10,
                ],
                [
                    'name' => 'Motley',
                    'slug' => 'motley',
                    'inheritance_mode' => 'recessive',
                    'description' => 'Vererbungsmodus: rezessiv. Erzeugt gleichmäßige Rückenzeichnung mit reduzierter Fleckung.',
                    'normal_label' => 'Wildtyp',
                    'heterozygous_label' => 'het Motley',
                    'homozygous_label' => 'Motley',
                    'display_order' => 11,
                ],
                [
                    'name' => 'Pink Pastel',
                    'slug' => 'pink-pastel',
                    'inheritance_mode' => 'polygenic',
                    'description' => 'Vererbungsmodus: polygen. Selektive Linie für rosafarbene Tiere.',
                    'normal_label' => 'Linienzucht',
                    'heterozygous_label' => 'Pink Pastel',
                    'homozygous_label' => 'Pink Pastel',
                    'display_order' => 12,
                ],
                [
                    'name' => 'RBE Pastel',
                    'slug' => 'rbe-pastel',
                    'inheritance_mode' => 'polygenic',
                    'description' => 'Vererbungsmodus: polygen. Riverbend Exotics Pastel-Linie zur Aufhellung.',
                    'normal_label' => 'Linienzucht',
                    'heterozygous_label' => 'RBE Pastel',
                    'homozygous_label' => 'RBE Pastel',
                    'display_order' => 13,
                ],
                [
                    'name' => 'Sable',
                    'slug' => 'sable',
                    'inheritance_mode' => 'recessive',
                    'description' => 'Vererbungsmodus: rezessiv. Bildet dunkle Sepiatöne und reduziert Musterung.',
                    'normal_label' => 'Wildtyp',
                    'heterozygous_label' => 'het Sable',
                    'homozygous_label' => 'Sable',
                    'display_order' => 14,
                ],
                [
                    'name' => 'Shadow',
                    'slug' => 'shadow',
                    'inheritance_mode' => 'polygenic',
                    'description' => 'Vererbungsmodus: polygen. Dunkelt Tiere linienbasiert ab und verstärkt Kontraste.',
                    'normal_label' => 'Linienzucht',
                    'heterozygous_label' => 'Shadow',
                    'homozygous_label' => 'Shadow',
                    'display_order' => 15,
                ],
                [
                    'name' => 'Speckled',
                    'slug' => 'speckled',
                    'inheritance_mode' => 'polygenic',
                    'description' => 'Vererbungsmodus: polygen. Erhöht die Sprenkelung im dorsalen Bereich.',
                    'normal_label' => 'Linienzucht',
                    'heterozygous_label' => 'Speckled',
                    'homozygous_label' => 'Speckled',
                    'display_order' => 16,
                ],
                [
                    'name' => 'Swiss Chocolate',
                    'slug' => 'swiss-chocolate',
                    'inheritance_mode' => 'recessive',
                    'description' => 'Vererbungsmodus: rezessiv. T+ Albino-Linie mit satten Schokoladentönen.',
                    'normal_label' => 'Wildtyp',
                    'heterozygous_label' => 'het Swiss Chocolate',
                    'homozygous_label' => 'Swiss Chocolate',
                    'display_order' => 17,
                ],
                [
                    'name' => 'Toffee Belly',
                    'slug' => 'toffee-belly',
                    'inheritance_mode' => 'recessive',
                    'description' => 'Vererbungsmodus: rezessiv. Auch als T+ Albino bekannt; erzeugt warme Toffee-Färbung.',
                    'normal_label' => 'Wildtyp',
                    'heterozygous_label' => 'het Toffee Belly',
                    'homozygous_label' => 'Toffee Belly',
                    'display_order' => 18,
                ],
                [
                    'name' => 'Toxic',
                    'slug' => 'toxic',
                    'inheritance_mode' => 'incomplete_dominant',
                    'description' => 'Vererbungsmodus: inkomplett dominant. Super-Form führt zu nahezu vollflächiger Aufhellung.',
                    'normal_label' => 'Wildtyp',
                    'heterozygous_label' => 'Toxic',
                    'homozygous_label' => 'Super Toxic',
                    'display_order' => 19,
                ],
                [
                    'name' => 'Normal',
                    'slug' => 'normal',
                    'inheritance_mode' => 'other',
                    'description' => 'Referenz für Wildtyp-Tiere ohne nachgewiesene Mutationsmerkmale.',
                    'normal_label' => 'Standard',
                    'heterozygous_label' => 'Normal',
                    'homozygous_label' => 'Normal',
                    'display_order' => 20,
                ],
            ],
        ],
        'pogona-vitticeps' => [
            'species' => [
                'name' => 'Pogona vitticeps',
                'slug' => 'pogona-vitticeps',
                'scientific_name' => 'Pogona vitticeps',
                'description' => 'Bartagamen (<em>Pogona vitticeps</em>) zeigen eine Mischung aus rezessiven, dominanten und polygene Linien. Die Morphpedia-Daten liefern eine Grundlage für Farb- und Zeichnungszuchten innerhalb des Rechners.',
            ],
            'genes' => [
                [
                    'name' => 'Brown/tan/sand',
                    'slug' => 'browntansand',
                    'inheritance_mode' => 'polygenic',
                    'description' => 'Vererbungsmodus laut Morphpedia: polygen.',
                    'normal_label' => 'Linienzucht',
                    'heterozygous_label' => 'Brown/tan/sand',
                    'homozygous_label' => 'Brown/tan/sand',
                    'display_order' => 1,
                ],
                [
                    'name' => 'Dunner',
                    'slug' => 'dunner',
                    'inheritance_mode' => 'dominant',
                    'description' => 'Vererbungsmodus laut Morphpedia: dominant. Zusatzangabe: Rarest.',
                    'normal_label' => 'Wildtyp',
                    'heterozygous_label' => 'Dunner',
                    'homozygous_label' => 'Dunner (Super)',
                    'display_order' => 2,
                ],
                [
                    'name' => 'Genetic Stripe',
                    'slug' => 'genetic-stripe',
                    'inheritance_mode' => 'dominant',
                    'description' => 'Vererbungsmodus laut Morphpedia: dominant. Zusatzangabe: Rarest.',
                    'normal_label' => 'Wildtyp',
                    'heterozygous_label' => 'Genetic Stripe',
                    'homozygous_label' => 'Genetic Stripe (Super)',
                    'display_order' => 3,
                ],
                [
                    'name' => 'Hypo',
                    'slug' => 'hypo',
                    'inheritance_mode' => 'recessive',
                    'description' => 'Vererbungsmodus laut Morphpedia: rezessiv. Zusatzangabe: Common.',
                    'normal_label' => 'Wildtyp',
                    'heterozygous_label' => 'het Hypo',
                    'homozygous_label' => 'Hypo',
                    'display_order' => 4,
                ],
                [
                    'name' => 'Leatherback',
                    'slug' => 'leatherback',
                    'inheritance_mode' => 'incomplete_dominant',
                    'description' => 'Vererbungsmodus laut Morphpedia: inkomplett dominant. Zusatzangabe: Rarest.',
                    'normal_label' => 'Wildtyp',
                    'heterozygous_label' => 'Leatherback',
                    'homozygous_label' => 'Super Leatherback',
                    'display_order' => 5,
                ],
                [
                    'name' => 'Normal',
                    'slug' => 'normal',
                    'inheritance_mode' => 'other',
                    'description' => 'Vererbungsmodus laut Morphpedia: sonstige Kategorie. Zusatzangabe: Rarest.',
                    'normal_label' => 'Standard',
                    'heterozygous_label' => 'Normal',
                    'homozygous_label' => 'Normal',
                    'display_order' => 6,
                ],
                [
                    'name' => 'Orange',
                    'slug' => 'orange',
                    'inheritance_mode' => 'polygenic',
                    'description' => 'Vererbungsmodus laut Morphpedia: polygen. Zusatzangabe: Common.',
                    'normal_label' => 'Linienzucht',
                    'heterozygous_label' => 'Orange',
                    'homozygous_label' => 'Orange',
                    'display_order' => 7,
                ],
                [
                    'name' => 'Paradox',
                    'slug' => 'paradox',
                    'inheritance_mode' => 'other',
                    'description' => 'Vererbungsmodus laut Morphpedia: sonstige Kategorie. Zusatzangabe: Rarest.',
                    'normal_label' => 'Standard',
                    'heterozygous_label' => 'Paradox',
                    'homozygous_label' => 'Paradox',
                    'display_order' => 8,
                ],
                [
                    'name' => 'Purple/blue',
                    'slug' => 'purpleblue',
                    'inheritance_mode' => 'polygenic',
                    'description' => 'Vererbungsmodus laut Morphpedia: polygen.',
                    'normal_label' => 'Linienzucht',
                    'heterozygous_label' => 'Purple/blue',
                    'homozygous_label' => 'Purple/blue',
                    'display_order' => 9,
                ],
                [
                    'name' => 'Recessive Leatherback',
                    'slug' => 'recessive-leatherback',
                    'inheritance_mode' => 'recessive',
                    'description' => 'Vererbungsmodus laut Morphpedia: rezessiv. Zusatzangabe: 2004 Rarest.',
                    'normal_label' => 'Wildtyp',
                    'heterozygous_label' => 'het Recessive Leatherback',
                    'homozygous_label' => 'Recessive Leatherback',
                    'display_order' => 10,
                ],
                [
                    'name' => 'Red',
                    'slug' => 'red',
                    'inheritance_mode' => 'polygenic',
                    'description' => 'Vererbungsmodus laut Morphpedia: polygen. Zusatzangabe: Common.',
                    'normal_label' => 'Linienzucht',
                    'heterozygous_label' => 'Red',
                    'homozygous_label' => 'Red',
                    'display_order' => 11,
                ],
                [
                    'name' => 'Translucent',
                    'slug' => 'translucent',
                    'inheritance_mode' => 'recessive',
                    'description' => 'Vererbungsmodus laut Morphpedia: rezessiv. Zusatzangabe: Common.',
                    'normal_label' => 'Wildtyp',
                    'heterozygous_label' => 'het Translucent',
                    'homozygous_label' => 'Translucent',
                    'display_order' => 12,
                ],
                [
                    'name' => 'White',
                    'slug' => 'white',
                    'inheritance_mode' => 'polygenic',
                    'description' => 'Vererbungsmodus laut Morphpedia: polygen. Zusatzangabe: Rarest.',
                    'normal_label' => 'Linienzucht',
                    'heterozygous_label' => 'White',
                    'homozygous_label' => 'White',
                    'display_order' => 13,
                ],
                [
                    'name' => 'Witblits',
                    'slug' => 'witblits',
                    'inheritance_mode' => 'recessive',
                    'description' => 'Vererbungsmodus laut Morphpedia: rezessiv. Zusatzangabe: Rarest.',
                    'normal_label' => 'Wildtyp',
                    'heterozygous_label' => 'het Witblits',
                    'homozygous_label' => 'Witblits',
                    'display_order' => 14,
                ],
                [
                    'name' => 'Yellow/citrus',
                    'slug' => 'yellowcitrus',
                    'inheritance_mode' => 'polygenic',
                    'description' => 'Vererbungsmodus laut Morphpedia: polygen.',
                    'normal_label' => 'Linienzucht',
                    'heterozygous_label' => 'Yellow/citrus',
                    'homozygous_label' => 'Yellow/citrus',
                    'display_order' => 15,
                ],
                [
                    'name' => 'Zero',
                    'slug' => 'zero',
                    'inheritance_mode' => 'recessive',
                    'description' => 'Vererbungsmodus laut Morphpedia: rezessiv. Zusatzangabe: Rarest.',
                    'normal_label' => 'Wildtyp',
                    'heterozygous_label' => 'het Zero',
                    'homozygous_label' => 'Zero',
                    'display_order' => 16,
                ],
            ],
        ],
    ];
}

function get_genetic_species(PDO $pdo): array
{
    return $pdo->query('SELECT * FROM genetic_species ORDER BY name ASC')->fetchAll();
}

function get_genetic_species_by_id(PDO $pdo, int $id): ?array
{
    $stmt = $pdo->prepare('SELECT * FROM genetic_species WHERE id = :id');
    $stmt->execute(['id' => $id]);
    $row = $stmt->fetch();
    return $row ?: null;
}

function get_genetic_species_by_slug(PDO $pdo, string $slug): ?array
{
    $stmt = $pdo->prepare('SELECT * FROM genetic_species WHERE slug = :slug');
    $stmt->execute(['slug' => $slug]);
    $row = $stmt->fetch();
    return $row ?: null;
}

function create_genetic_species(PDO $pdo, array $data): int
{
    $name = trim($data['name'] ?? '');
    if ($name === '') {
        throw new InvalidArgumentException('Species name is required.');
    }

    $slug = trim($data['slug'] ?? '');
    if ($slug === '') {
        $slug = slugify($name);
    }
    $slug = ensure_unique_slug($pdo, 'genetic_species', $slug);

    $stmt = $pdo->prepare('INSERT INTO genetic_species(name, slug, scientific_name, description) VALUES (:name, :slug, :scientific_name, :description)');
    $stmt->execute([
        'name' => $name,
        'slug' => $slug,
        'scientific_name' => trim($data['scientific_name'] ?? '') ?: null,
        'description' => trim($data['description'] ?? '') ?: null,
    ]);

    return (int)$pdo->lastInsertId();
}

function update_genetic_species(PDO $pdo, int $id, array $data): void
{
    $name = trim($data['name'] ?? '');
    if ($name === '') {
        throw new InvalidArgumentException('Species name is required.');
    }

    $slug = trim($data['slug'] ?? '');
    if ($slug === '') {
        $slug = slugify($name);
    }
    $slug = ensure_unique_slug($pdo, 'genetic_species', $slug, $id);

    $stmt = $pdo->prepare('UPDATE genetic_species SET name = :name, slug = :slug, scientific_name = :scientific_name, description = :description, updated_at = CURRENT_TIMESTAMP WHERE id = :id');
    $stmt->execute([
        'name' => $name,
        'slug' => $slug,
        'scientific_name' => trim($data['scientific_name'] ?? '') ?: null,
        'description' => trim($data['description'] ?? '') ?: null,
        'id' => $id,
    ]);
}

function delete_genetic_species(PDO $pdo, int $id): void
{
    $stmt = $pdo->prepare('DELETE FROM genetic_species WHERE id = :id');
    $stmt->execute(['id' => $id]);
}

function get_genetic_genes(PDO $pdo, int $speciesId): array
{
    $stmt = $pdo->prepare('SELECT * FROM genetic_genes WHERE species_id = :species ORDER BY display_order ASC, name ASC');
    $stmt->execute(['species' => $speciesId]);
    return $stmt->fetchAll();
}

function get_all_genetic_genes(PDO $pdo): array
{
    return $pdo->query('SELECT * FROM genetic_genes')->fetchAll();
}

function get_genetic_gene(PDO $pdo, int $id): ?array
{
    $stmt = $pdo->prepare('SELECT * FROM genetic_genes WHERE id = :id');
    $stmt->execute(['id' => $id]);
    $row = $stmt->fetch();
    return $row ?: null;
}

function normalize_inheritance_mode(string $mode): string
{
    $mode = strtolower(trim($mode));
    if (!in_array($mode, GENETIC_INHERITANCE_MODES, true)) {
        return 'recessive';
    }
    return $mode;
}

function ensure_unique_gene_slug(PDO $pdo, int $speciesId, string $slug, ?int $ignoreId = null): string
{
    $base = $slug ?: bin2hex(random_bytes(4));
    $candidate = $base;
    $counter = 1;

    while (true) {
        $sql = 'SELECT COUNT(*) FROM genetic_genes WHERE species_id = :species AND slug = :slug';
        $params = ['species' => $speciesId, 'slug' => $candidate];
        if ($ignoreId !== null) {
            $sql .= ' AND id != :id';
            $params['id'] = $ignoreId;
        }
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        if ((int)$stmt->fetchColumn() === 0) {
            return $candidate;
        }
        $candidate = $base . '-' . (++$counter);
    }
}

function create_genetic_gene(PDO $pdo, array $data): int
{
    $speciesId = (int)($data['species_id'] ?? 0);
    if ($speciesId <= 0) {
        throw new InvalidArgumentException('Species reference missing.');
    }
    $name = trim($data['name'] ?? '');
    if ($name === '') {
        throw new InvalidArgumentException('Gene name is required.');
    }

    $slug = trim($data['slug'] ?? '');
    if ($slug === '') {
        $slug = slugify($name);
    }
    $slug = ensure_unique_gene_slug($pdo, $speciesId, $slug);

    $inheritance = normalize_inheritance_mode($data['inheritance_mode'] ?? '');

    $stmt = $pdo->prepare('INSERT INTO genetic_genes(species_id, name, slug, shorthand, inheritance_mode, description, normal_label, heterozygous_label, homozygous_label, display_order) VALUES (:species_id, :name, :slug, :shorthand, :inheritance_mode, :description, :normal_label, :heterozygous_label, :homozygous_label, :display_order)');
    $stmt->execute([
        'species_id' => $speciesId,
        'name' => $name,
        'slug' => $slug,
        'shorthand' => trim($data['shorthand'] ?? '') ?: null,
        'inheritance_mode' => $inheritance,
        'description' => trim($data['description'] ?? '') ?: null,
        'normal_label' => trim($data['normal_label'] ?? '') ?: null,
        'heterozygous_label' => trim($data['heterozygous_label'] ?? '') ?: null,
        'homozygous_label' => trim($data['homozygous_label'] ?? '') ?: null,
        'display_order' => isset($data['display_order']) ? (int)$data['display_order'] : 0,
    ]);

    return (int)$pdo->lastInsertId();
}

function update_genetic_gene(PDO $pdo, int $id, array $data): void
{
    $gene = get_genetic_gene($pdo, $id);
    if (!$gene) {
        throw new InvalidArgumentException('Gene not found.');
    }

    $name = trim($data['name'] ?? '');
    if ($name === '') {
        throw new InvalidArgumentException('Gene name is required.');
    }

    $slug = trim($data['slug'] ?? '');
    if ($slug === '') {
        $slug = slugify($name);
    }
    $slug = ensure_unique_gene_slug($pdo, (int)$gene['species_id'], $slug, $id);

    $inheritance = normalize_inheritance_mode($data['inheritance_mode'] ?? $gene['inheritance_mode']);

    $stmt = $pdo->prepare('UPDATE genetic_genes SET name = :name, slug = :slug, shorthand = :shorthand, inheritance_mode = :inheritance_mode, description = :description, normal_label = :normal_label, heterozygous_label = :heterozygous_label, homozygous_label = :homozygous_label, display_order = :display_order, updated_at = CURRENT_TIMESTAMP WHERE id = :id');
    $stmt->execute([
        'name' => $name,
        'slug' => $slug,
        'shorthand' => trim($data['shorthand'] ?? '') ?: null,
        'inheritance_mode' => $inheritance,
        'description' => trim($data['description'] ?? '') ?: null,
        'normal_label' => trim($data['normal_label'] ?? '') ?: null,
        'heterozygous_label' => trim($data['heterozygous_label'] ?? '') ?: null,
        'homozygous_label' => trim($data['homozygous_label'] ?? '') ?: null,
        'display_order' => isset($data['display_order']) ? (int)$data['display_order'] : (int)$gene['display_order'],
        'id' => $id,
    ]);
}

function delete_genetic_gene(PDO $pdo, int $id): void
{
    $stmt = $pdo->prepare('DELETE FROM genetic_genes WHERE id = :id');
    $stmt->execute(['id' => $id]);
}

function gene_inheritance_is_supported(array $gene): bool
{
    $mode = $gene['inheritance_mode'] ?? '';
    return in_array($mode, GENETIC_CALCULATOR_MODES, true);
}

function gene_state_to_alleles(string $state): array
{
    return match ($state) {
        'homozygous' => [1, 1],
        'heterozygous' => [1, 0],
        default => [0, 0],
    };
}

function allele_sum_to_state(int $sum): string
{
    return match ($sum) {
        2 => 'homozygous',
        1 => 'heterozygous',
        default => 'normal',
    };
}

function sanitize_gene_state(?string $state): string
{
    $state = strtolower((string)$state);
    return match ($state) {
        'homozygous', 'super' => 'homozygous',
        'heterozygous', 'het' => 'heterozygous',
        default => 'normal',
    };
}

function gene_state_label(array $gene, string $state): string
{
    $inheritance = $gene['inheritance_mode'];
    $name = $gene['name'];

    $normalDefault = $gene['normal_label'] ?: 'Wildtyp';
    $heteroDefault = $gene['heterozygous_label'];
    $homoDefault = $gene['homozygous_label'];

    if (!$heteroDefault) {
        if ($inheritance === 'recessive') {
            $heteroDefault = 'het ' . $name;
        } else {
            $heteroDefault = $name;
        }
    }
    if (!$homoDefault) {
        if ($inheritance === 'recessive') {
            $homoDefault = $name;
        } elseif ($inheritance === 'dominant') {
            $homoDefault = $name . ' (Super)';
        } else {
            $homoDefault = 'Super ' . $name;
        }
    }

    return match ($state) {
        'heterozygous' => $heteroDefault,
        'homozygous' => $homoDefault,
        default => $normalDefault,
    };
}

function gene_state_is_visual(array $gene, string $state): bool
{
    return match ($gene['inheritance_mode']) {
        'recessive' => $state === 'homozygous',
        'dominant', 'incomplete_dominant' => in_array($state, ['heterozygous', 'homozygous'], true),
        default => false,
    };
}

function gene_state_is_carrier(array $gene, string $state): bool
{
    if ($gene['inheritance_mode'] === 'recessive' && $state === 'heterozygous') {
        return true;
    }
    return false;
}

function gene_state_appearance_bucket(array $gene, string $state): string
{
    $mode = $gene['inheritance_mode'] ?? 'recessive';
    switch ($mode) {
        case 'recessive':
            return $state === 'homozygous' ? 'visual' : 'hidden';
        case 'dominant':
            return $state === 'normal' ? 'hidden' : 'visual';
        case 'incomplete_dominant':
            if ($state === 'homozygous') {
                return 'super';
            }
            if ($state === 'heterozygous') {
                return 'visual';
            }
            return 'hidden';
        default:
            return 'hidden';
    }
}

function genetic_gcd(int $a, int $b): int
{
    $a = abs($a);
    $b = abs($b);
    if ($a === 0) {
        return $b === 0 ? 1 : $b;
    }
    if ($b === 0) {
        return $a;
    }
    while ($b !== 0) {
        $temp = $b;
        $b = $a % $b;
        $a = $temp;
    }
    return $a === 0 ? 1 : $a;
}

function probability_to_fraction_components(float $probability, int $maxDenominator = 1024): array
{
    $probability = max(0.0, min(1.0, $probability));
    $denominator = 1;
    $epsilon = 1e-6;

    while ($denominator < $maxDenominator) {
        $scaled = $probability * $denominator;
        if (abs(round($scaled) - $scaled) < $epsilon) {
            break;
        }
        $denominator *= 2;
    }

    $numerator = (int)round($probability * $denominator);
    if ($numerator === 0 && $probability > 0) {
        $numerator = 1;
    }

    $gcd = genetic_gcd($numerator, $denominator);

    return [
        'numerator' => (int)max(0, $numerator / $gcd),
        'denominator' => (int)max(1, $denominator / $gcd),
        'percentage' => $probability * 100,
    ];
}

function calculate_gene_distribution(array $gene, string $parentOneState, string $parentTwoState): array
{
    $allelesOne = gene_state_to_alleles($parentOneState);
    $allelesTwo = gene_state_to_alleles($parentTwoState);

    $distribution = [];
    $total = 0;
    foreach ($allelesOne as $alleleOne) {
        foreach ($allelesTwo as $alleleTwo) {
            $state = allele_sum_to_state($alleleOne + $alleleTwo);
            $distribution[$state] = ($distribution[$state] ?? 0) + 1;
            $total++;
        }
    }

    foreach ($distribution as $state => $count) {
        $distribution[$state] = $count / $total;
    }

    ksort($distribution);

    $states = [];
    foreach ($distribution as $state => $probability) {
        $states[] = [
            'state' => $state,
            'probability' => $probability,
            'label' => gene_state_label($gene, $state),
            'is_visual' => gene_state_is_visual($gene, $state),
            'is_carrier' => gene_state_is_carrier($gene, $state),
        ];
    }

    usort($states, static function ($a, $b) {
        return $b['probability'] <=> $a['probability'];
    });

    return $states;
}

function calculate_genetic_outcomes(array $genes, array $parentOneSelections, array $parentTwoSelections): ?array
{
    $geneResults = [];

    foreach ($genes as $gene) {
        if (!gene_inheritance_is_supported($gene)) {
            continue;
        }
        $geneId = (int)$gene['id'];
        $stateOne = sanitize_gene_state($parentOneSelections[$geneId] ?? null);
        $stateTwo = sanitize_gene_state($parentTwoSelections[$geneId] ?? null);

        if ($stateOne === 'normal' && $stateTwo === 'normal') {
            continue;
        }

        $states = calculate_gene_distribution($gene, $stateOne, $stateTwo);
        $geneResults[$geneId] = [
            'gene' => $gene,
            'states' => $states,
            'parent_states' => [
                'parent_one' => $stateOne,
                'parent_two' => $stateTwo,
            ],
        ];
    }

    if (empty($geneResults)) {
        return null;
    }

    $combined = [
        [
            'probability' => 1.0,
            'states' => [],
            'labels' => [],
            'visual_traits' => [],
            'carrier_traits' => [],
        ],
    ];

    foreach ($geneResults as $geneId => $geneResult) {
        $geneStates = $geneResult['states'];
        $nextCombined = [];
        foreach ($combined as $entry) {
            foreach ($geneStates as $stateInfo) {
                $newStates = $entry['states'];
                $newStates[$geneId] = $stateInfo['state'];

                $newLabels = $entry['labels'];
                $newLabels[$geneId] = $stateInfo['label'];

                $visual = $entry['visual_traits'];
                $carriers = $entry['carrier_traits'];

                if ($stateInfo['is_visual']) {
                    $visual[$geneId] = $stateInfo['label'];
                } elseif ($stateInfo['is_carrier']) {
                    $carriers[$geneId] = $stateInfo['label'];
                }

                $nextCombined[] = [
                    'probability' => $entry['probability'] * $stateInfo['probability'],
                    'states' => $newStates,
                    'labels' => $newLabels,
                    'visual_traits' => $visual,
                    'carrier_traits' => $carriers,
                ];
            }
        }
        $combined = $nextCombined;
    }

    if (empty($combined)) {
        return [
            'genes' => $geneResults,
            'combined' => [],
        ];
    }

    $grouped = [];
    foreach ($combined as $entry) {
        $appearanceKeyParts = [];
        foreach ($entry['states'] as $geneId => $stateKey) {
            $gene = $geneResults[$geneId]['gene'];
            $bucket = gene_state_appearance_bucket($gene, $stateKey);
            if (in_array($bucket, ['visual', 'super'], true)) {
                $appearanceKeyParts[] = $geneId . ':' . $bucket;
            }
        }
        sort($appearanceKeyParts);
        $key = implode('|', $appearanceKeyParts);
        if (!isset($grouped[$key])) {
            $grouped[$key] = [
                'probability' => 0.0,
                'state_distribution' => [],
                'raw_entries' => [],
            ];
        }
        $grouped[$key]['probability'] += $entry['probability'];
        $grouped[$key]['raw_entries'][] = $entry;
        foreach ($entry['states'] as $geneId => $stateKey) {
            if (!isset($grouped[$key]['state_distribution'][$geneId])) {
                $grouped[$key]['state_distribution'][$geneId] = [];
            }
            $grouped[$key]['state_distribution'][$geneId][$stateKey] = ($grouped[$key]['state_distribution'][$geneId][$stateKey] ?? 0) + $entry['probability'];
        }
    }

    $combinedResults = [];
    foreach ($grouped as $group) {
        $probability = $group['probability'];
        if ($probability <= 0) {
            continue;
        }

        $visualTags = [];
        $carrierTags = [];
        $possibleCarrierTags = [];
        $stateDetails = [];

        foreach ($group['state_distribution'] as $geneId => $stateWeights) {
            $gene = $geneResults[$geneId]['gene'];
            $labels = [];
            $probabilities = [];
            foreach ($stateWeights as $stateKey => $weight) {
                $probabilities[$stateKey] = $weight / $probability;
                $labels[$stateKey] = gene_state_label($gene, $stateKey);
            }
            $stateDetails[$geneId] = [
                'gene' => $gene,
                'probabilities' => $probabilities,
                'labels' => $labels,
            ];

            $visualProbability = 0.0;
            $carrierProbability = 0.0;
            $visualState = null;
            $visualStateProbability = -1.0;

            foreach ($probabilities as $stateKey => $stateProbability) {
                if (gene_state_is_visual($gene, $stateKey)) {
                    $visualProbability += $stateProbability;
                    if ($stateProbability > $visualStateProbability) {
                        $visualStateProbability = $stateProbability;
                        $visualState = $stateKey;
                    }
                }
                if (gene_state_is_carrier($gene, $stateKey)) {
                    $carrierProbability += $stateProbability;
                }
            }

            if ($visualProbability > 0) {
                $label = $visualState ? $labels[$visualState] : ($labels['homozygous'] ?? ($labels['heterozygous'] ?? $gene['name']));
                $tagType = 'visual';
                if ($gene['inheritance_mode'] === 'incomplete_dominant' && $visualState === 'homozygous') {
                    $tagType = 'visual-super';
                }
                $visualTags[] = [
                    'label' => $label,
                    'type' => $tagType,
                    'gene_id' => $geneId,
                ];
            }

            if ($carrierProbability > 0) {
                $percentage = (int)round($carrierProbability * 100);
                $baseLabel = $labels['heterozygous'] ?? ('Het ' . $gene['name']);
                $cleanLabel = preg_replace('/^het\s+/i', 'Het ', $baseLabel ?? $gene['name']);
                if ($carrierProbability >= 0.999) {
                    $carrierTags[] = [
                        'label' => '100% ' . $cleanLabel,
                        'gene_id' => $geneId,
                    ];
                } else {
                    $possibleCarrierTags[] = [
                        'label' => $percentage . '% ' . $cleanLabel,
                        'gene_id' => $geneId,
                        'percentage' => $percentage,
                    ];
                }
            }
        }

        $tags = array_merge($visualTags, array_map(static function ($tag) {
            $tag['type'] = 'carrier';
            return $tag;
        }, $carrierTags), array_map(static function ($tag) {
            $tag['type'] = 'possible-carrier';
            return $tag;
        }, $possibleCarrierTags));

        $morphParts = [];
        foreach ($visualTags as $tag) {
            $morphParts[] = $tag['label'];
        }
        foreach ($carrierTags as $tag) {
            $label = $tag['label'];
            if (!preg_match('/^\d+%/u', $label)) {
                $label = '100% ' . $label;
            }
            $morphParts[] = $label;
        }
        foreach ($possibleCarrierTags as $tag) {
            $morphParts[] = $tag['label'];
        }
        if (empty($morphParts)) {
            $morphName = 'Wildtyp';
        } else {
            $morphParts = array_values(array_unique($morphParts));
            $morphName = implode(' ', $morphParts);
        }

        $combinedResults[] = [
            'probability' => $probability,
            'tags' => $tags,
            'visual_tags' => $visualTags,
            'carrier_tags' => $carrierTags,
            'possible_carrier_tags' => $possibleCarrierTags,
            'state_details' => $stateDetails,
            'morph_name' => $morphName,
        ];
    }

    usort($combinedResults, static function ($a, $b) {
        return $b['probability'] <=> $a['probability'];
    });

    return [
        'genes' => $geneResults,
        'combined' => $combinedResults,
    ];
}

function ensure_default_genetics(PDO $pdo): void
{
    $catalog = default_genetics_catalog();
    $legacyDescriptions = [
        'heterodon-nasicus' => [
            'Western Hognoses (Heterodon nasicus) zeigen eine Vielzahl an rezessiven und inkomplett dominanten Morphen. Die Beispielkonfiguration liefert Startwerte für einen schnellen Einstieg in die Zuchtplanung.',
        ],
    ];

    foreach ($catalog as $slug => $config) {
        $speciesConfig = $config['species'];
        $existing = get_genetic_species_by_slug($pdo, $slug);
        if ($existing) {
            $speciesId = (int)$existing['id'];
            $currentDescription = trim((string)($existing['description'] ?? ''));
            if ($currentDescription === '' || in_array($currentDescription, $legacyDescriptions[$slug] ?? [], true)) {
                update_genetic_species($pdo, $speciesId, [
                    'name' => $speciesConfig['name'],
                    'slug' => $slug,
                    'scientific_name' => $speciesConfig['scientific_name'],
                    'description' => $speciesConfig['description'],
                ]);
            }
        } else {
            $speciesId = create_genetic_species($pdo, [
                'name' => $speciesConfig['name'],
                'slug' => $slug,
                'scientific_name' => $speciesConfig['scientific_name'],
                'description' => $speciesConfig['description'],
            ]);
        }

        foreach ($config['genes'] as $geneData) {
            $stmt = $pdo->prepare('SELECT id FROM genetic_genes WHERE species_id = :species AND slug = :slug');
            $stmt->execute([
                'species' => $speciesId,
                'slug' => $geneData['slug'],
            ]);
            $geneId = $stmt->fetchColumn();
            $payload = $geneData;
            $payload['species_id'] = $speciesId;
            if ($geneId) {
                update_genetic_gene($pdo, (int)$geneId, $payload);
            } else {
                create_genetic_gene($pdo, $payload);
            }
        }
    }
}

