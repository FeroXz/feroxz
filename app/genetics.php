<?php

function ensure_default_genetics(PDO $pdo): void
{
    $speciesData = [
        [
            'slug' => 'pogona-vitticeps',
            'name' => 'Bartagame',
            'scientific_name' => 'Pogona vitticeps',
            'description' => 'Australische Bartagamen zählen zu den beliebtesten Terrarientieren. Selektive Zuchtlinien ermöglichen vielfältige Farb- und Strukturausprägungen.',
            'genes' => [
                [
                    'slug' => 'hypomelanistic',
                    'name' => 'Hypomelanistic',
                    'inheritance' => 'codominant',
                    'description' => 'Reduzierte Melaninproduktion sorgt für hellere, pastellige Tiere. Superformen erscheinen fast farblos.',
                    'visual_label' => 'Hypomelanistic',
                    'heterozygous_label' => 'Hypo',
                    'homozygous_label' => 'Super Hypo',
                    'wild_label' => 'Normal'
                ],
                [
                    'slug' => 'leatherback',
                    'name' => 'Leatherback',
                    'inheritance' => 'codominant',
                    'description' => 'Verminderte Schuppenbildung führt zu einer glatten Rückenstruktur. Superformen („Silkback“) benötigen intensive Pflege.',
                    'visual_label' => 'Leatherback',
                    'heterozygous_label' => 'Leatherback',
                    'homozygous_label' => 'Silkback',
                    'wild_label' => 'Normalschuppe'
                ],
                [
                    'slug' => 'dunner',
                    'name' => 'Dunner',
                    'inheritance' => 'dominant',
                    'description' => 'Mutation mit chaotischem Schuppen- und Zeichnungsbild sowie verstärktem Appetit.',
                    'visual_label' => 'Dunner',
                    'homozygous_label' => 'Super Dunner',
                    'wild_label' => 'Normal'
                ],
                [
                    'slug' => 'zero',
                    'name' => 'Zero',
                    'inheritance' => 'recessive',
                    'description' => 'Pigmentarme Linie, die nahezu vollständig weiß/grau erscheint und keine Musterung zeigt.',
                    'visual_label' => 'Zero',
                    'heterozygous_label' => 'Het Zero',
                    'wild_label' => 'Normal'
                ],
                [
                    'slug' => 'witblits',
                    'name' => 'Witblits',
                    'inheritance' => 'recessive',
                    'description' => 'Komplett musterlose Tiere mit cremigem Grundton. Häufig in Kombination mit Hypo und Zero gezüchtet.',
                    'visual_label' => 'Witblits',
                    'heterozygous_label' => 'Het Witblits',
                    'wild_label' => 'Normal'
                ],
            ]
        ],
        [
            'slug' => 'heterodon-nasicus',
            'name' => 'Hakennasennatter',
            'scientific_name' => 'Heterodon nasicus',
            'description' => 'Die nordamerikanische Hakennasennatter zeigt eine enorme Vielfalt rezessiver und codominanter Mutationen.',
            'genes' => [
                [
                    'slug' => 'albino',
                    'name' => 'Albino',
                    'inheritance' => 'recessive',
                    'description' => 'Fehlendes Melanin erzeugt ein kontrastreiches gelb-rotes Erscheinungsbild mit roten Augen.',
                    'visual_label' => 'Albino',
                    'heterozygous_label' => 'Het Albino',
                    'wild_label' => 'Normal'
                ],
                [
                    'slug' => 'toffee-belly',
                    'name' => 'Toffee Belly',
                    'inheritance' => 'recessive',
                    'description' => 'Sorgt für karamellfarbene Unterseite und intensivierte Zeichnung. Kombiniert mit Albino entsteht „Toffino“.',
                    'visual_label' => 'Toffee Belly',
                    'heterozygous_label' => 'Het Toffee Belly',
                    'wild_label' => 'Normal'
                ],
                [
                    'slug' => 'conda',
                    'name' => 'Anaconda',
                    'inheritance' => 'codominant',
                    'description' => 'Reduziertes Fleckenmuster mit klaren Seitenlinien; Superform „Superconda“ besitzt kaum Zeichnung.',
                    'visual_label' => 'Conda',
                    'heterozygous_label' => 'Conda',
                    'homozygous_label' => 'Superconda',
                    'wild_label' => 'Normal'
                ],
                [
                    'slug' => 'axanthic',
                    'name' => 'Axanthic',
                    'inheritance' => 'recessive',
                    'description' => 'Reduzierte Gelb- und Rottöne lassen Tiere grau bis silbern wirken.',
                    'visual_label' => 'Axanthic',
                    'heterozygous_label' => 'Het Axanthic',
                    'wild_label' => 'Normal'
                ],
            ]
        ],
    ];

    foreach ($speciesData as $species) {
        $stmt = $pdo->prepare('INSERT OR IGNORE INTO genetic_species(slug, name, scientific_name, description) VALUES (:slug, :name, :scientific_name, :description)');
        $stmt->execute([
            'slug' => $species['slug'],
            'name' => $species['name'],
            'scientific_name' => $species['scientific_name'],
            'description' => $species['description'],
        ]);

        $speciesId = (int)$pdo->query('SELECT id FROM genetic_species WHERE slug = ' . $pdo->quote($species['slug']))->fetchColumn();

        $stmtGene = $pdo->prepare('INSERT OR IGNORE INTO genetic_genes(species_id, slug, name, inheritance, description, visual_label, heterozygous_label, homozygous_label, wild_label) VALUES (:species_id, :slug, :name, :inheritance, :description, :visual_label, :heterozygous_label, :homozygous_label, :wild_label)');
        foreach ($species['genes'] as $gene) {
            $gene['species_id'] = $speciesId;
            $stmtGene->execute($gene);
        }
    }
}

function get_genetic_species(PDO $pdo): array
{
    return $pdo->query('SELECT * FROM genetic_species ORDER BY name ASC')->fetchAll();
}

function get_genetic_species_by_id(PDO $pdo, int $id): ?array
{
    $stmt = $pdo->prepare('SELECT * FROM genetic_species WHERE id = :id');
    $stmt->execute(['id' => $id]);
    $species = $stmt->fetch();
    return $species ?: null;
}

function get_genetic_species_by_slug(PDO $pdo, string $slug): ?array
{
    $stmt = $pdo->prepare('SELECT * FROM genetic_species WHERE slug = :slug');
    $stmt->execute(['slug' => $slug]);
    $species = $stmt->fetch();
    return $species ?: null;
}

function get_genes_for_species(PDO $pdo, int $speciesId): array
{
    $stmt = $pdo->prepare('SELECT * FROM genetic_genes WHERE species_id = :species ORDER BY name ASC');
    $stmt->execute(['species' => $speciesId]);
    return $stmt->fetchAll();
}

function get_gene(PDO $pdo, int $id): ?array
{
    $stmt = $pdo->prepare('SELECT * FROM genetic_genes WHERE id = :id');
    $stmt->execute(['id' => $id]);
    $gene = $stmt->fetch();
    return $gene ?: null;
}

function get_gene_by_slug(PDO $pdo, int $speciesId, string $slug): ?array
{
    $stmt = $pdo->prepare('SELECT * FROM genetic_genes WHERE species_id = :species AND slug = :slug');
    $stmt->execute(['species' => $speciesId, 'slug' => $slug]);
    $gene = $stmt->fetch();
    return $gene ?: null;
}

function create_gene(PDO $pdo, array $data): void
{
    $stmt = $pdo->prepare('INSERT INTO genetic_genes(species_id, slug, name, inheritance, description, visual_label, heterozygous_label, homozygous_label, wild_label) VALUES (:species_id, :slug, :name, :inheritance, :description, :visual_label, :heterozygous_label, :homozygous_label, :wild_label)');
    $stmt->execute([
        'species_id' => $data['species_id'],
        'slug' => $data['slug'],
        'name' => $data['name'],
        'inheritance' => $data['inheritance'],
        'description' => $data['description'] ?? null,
        'visual_label' => $data['visual_label'] ?? null,
        'heterozygous_label' => $data['heterozygous_label'] ?? null,
        'homozygous_label' => $data['homozygous_label'] ?? null,
        'wild_label' => $data['wild_label'] ?? 'Wildtyp',
    ]);
}

function update_gene(PDO $pdo, int $id, array $data): void
{
    $stmt = $pdo->prepare('UPDATE genetic_genes SET slug = :slug, name = :name, inheritance = :inheritance, description = :description, visual_label = :visual_label, heterozygous_label = :heterozygous_label, homozygous_label = :homozygous_label, wild_label = :wild_label WHERE id = :id');
    $stmt->execute([
        'slug' => $data['slug'],
        'name' => $data['name'],
        'inheritance' => $data['inheritance'],
        'description' => $data['description'] ?? null,
        'visual_label' => $data['visual_label'] ?? null,
        'heterozygous_label' => $data['heterozygous_label'] ?? null,
        'homozygous_label' => $data['homozygous_label'] ?? null,
        'wild_label' => $data['wild_label'] ?? 'Wildtyp',
        'id' => $id,
    ]);
}

function delete_gene(PDO $pdo, int $id): void
{
    $stmt = $pdo->prepare('DELETE FROM genetic_genes WHERE id = :id');
    $stmt->execute(['id' => $id]);
}

function gene_state_options(array $gene): array
{
    $inheritance = strtolower($gene['inheritance']);
    $options = [['value' => $gene['slug'] . ':wild', 'label' => ($gene['wild_label'] ?: 'Wildtyp') . ' (' . $gene['name'] . ')']];

    if ($inheritance === 'recessive') {
        $options[] = ['value' => $gene['slug'] . ':het', 'label' => $gene['heterozygous_label'] ?: ('Het ' . $gene['name'])];
        $options[] = ['value' => $gene['slug'] . ':visual', 'label' => $gene['visual_label'] ?: $gene['name']];
    } elseif ($inheritance === 'codominant') {
        $options[] = ['value' => $gene['slug'] . ':het', 'label' => $gene['heterozygous_label'] ?: $gene['name']];
        $options[] = ['value' => $gene['slug'] . ':super', 'label' => $gene['homozygous_label'] ?: ('Super ' . $gene['name'])];
    } else { // dominant
        $options[] = ['value' => $gene['slug'] . ':visual', 'label' => $gene['visual_label'] ?: $gene['name']];
        $options[] = ['value' => $gene['slug'] . ':super', 'label' => $gene['homozygous_label'] ?: ('Super ' . $gene['name'])];
    }

    return $options;
}

function parse_gene_selection(array $selection): array
{
    $result = [];
    foreach ($selection as $value) {
        if (!$value) {
            continue;
        }
        if (strpos($value, ':') === false) {
            continue;
        }
        [$slug, $state] = explode(':', $value, 2);
        $result[$slug] = $state;
    }
    return $result;
}

function gene_state_to_alleles(array $gene, string $state): array
{
    $inheritance = strtolower($gene['inheritance']);
    if ($inheritance === 'recessive') {
        return match ($state) {
            'visual' => ['r', 'r'],
            'het' => ['d', 'r'],
            default => ['d', 'd'],
        };
    }

    if ($inheritance === 'codominant') {
        return match ($state) {
            'super' => ['c', 'c'],
            'het', 'visual' => ['c', 'w'],
            default => ['w', 'w'],
        };
    }

    // dominant
    return match ($state) {
        'super' => ['d', 'd'],
        'visual' => ['d', 'w'],
        default => ['w', 'w'],
    };
}

function describe_genotype(array $gene, string $inheritance, string $pair): array
{
    $pair = str_split($pair);
    sort($pair);
    $key = implode('', $pair);
    $inheritance = strtolower($inheritance);

    if ($inheritance === 'recessive') {
        return match ($key) {
            'rr' => ['label' => $gene['visual_label'] ?: $gene['name'], 'type' => 'visual'],
            'dr' => ['label' => $gene['heterozygous_label'] ?: ('Het ' . $gene['name']), 'type' => 'het'],
            default => ['label' => $gene['wild_label'] ?: 'Wildtyp', 'type' => 'wild'],
        };
    }

    if ($inheritance === 'codominant') {
        return match ($key) {
            'cc' => ['label' => $gene['homozygous_label'] ?: ('Super ' . $gene['name']), 'type' => 'visual'],
            'cw' => ['label' => $gene['heterozygous_label'] ?: $gene['name'], 'type' => 'het'],
            default => ['label' => $gene['wild_label'] ?: 'Wildtyp', 'type' => 'wild'],
        };
    }

    // dominant
    return match ($key) {
        'dd' => ['label' => $gene['homozygous_label'] ?: ($gene['name'] . ' (Super)'), 'type' => 'visual'],
        'dw' => ['label' => $gene['visual_label'] ?: $gene['name'], 'type' => 'visual'],
        default => ['label' => $gene['wild_label'] ?: 'Wildtyp', 'type' => 'wild'],
    };
}

function compute_gene_probabilities(array $gene, string $stateA, string $stateB): array
{
    $allelesA = gene_state_to_alleles($gene, $stateA);
    $allelesB = gene_state_to_alleles($gene, $stateB);

    $gametesA = [$allelesA[0], $allelesA[1]];
    $gametesB = [$allelesB[0], $allelesB[1]];

    $results = [];
    foreach ($gametesA as $a) {
        foreach ($gametesB as $b) {
            $pair = $a . $b;
            $description = describe_genotype($gene, $gene['inheritance'], $pair);
            $key = $description['type'] . '|' . $description['label'];
            if (!isset($results[$key])) {
                $results[$key] = ['label' => $description['label'], 'type' => $description['type'], 'count' => 0];
            }
            $results[$key]['count']++;
        }
    }

    $total = array_sum(array_column($results, 'count')) ?: 1;
    foreach ($results as &$result) {
        $result['probability'] = $result['count'] / $total;
        unset($result['count']);
    }

    usort($results, fn($a, $b) => $b['probability'] <=> $a['probability']);
    return $results;
}

function compute_genetics(PDO $pdo, int $speciesId, array $parentASelection, array $parentBSelection): array
{
    $genes = get_genes_for_species($pdo, $speciesId);
    $map = [];
    foreach ($genes as $gene) {
        $map[$gene['slug']] = $gene;
    }

    $parentA = parse_gene_selection($parentASelection);
    $parentB = parse_gene_selection($parentBSelection);

    $perGene = [];
    $combined = [['probability' => 1, 'parts' => []]];

    foreach ($map as $slug => $gene) {
        $stateA = $parentA[$slug] ?? 'wild';
        $stateB = $parentB[$slug] ?? 'wild';
        $outcomes = compute_gene_probabilities($gene, $stateA, $stateB);
        $perGene[] = ['gene' => $gene, 'outcomes' => $outcomes, 'stateA' => $stateA, 'stateB' => $stateB];

        $nextCombined = [];
        foreach ($combined as $combo) {
            foreach ($outcomes as $outcome) {
                $parts = $combo['parts'];
                if ($outcome['type'] !== 'wild') {
                    $parts[] = $outcome['label'];
                }
                $nextCombined[] = [
                    'probability' => $combo['probability'] * $outcome['probability'],
                    'parts' => $parts,
                ];
            }
        }
        $combined = $nextCombined;
    }

    $summaryMap = [];
    foreach ($combined as $combo) {
        $parts = $combo['parts'];
        sort($parts);
        $label = $parts ? implode(' + ', $parts) : 'Wildtyp / Normal';
        if (!isset($summaryMap[$label])) {
            $summaryMap[$label] = 0;
        }
        $summaryMap[$label] += $combo['probability'];
    }

    $summaries = [];
    foreach ($summaryMap as $label => $probability) {
        $summaries[] = ['label' => $label, 'probability' => $probability];
    }
    usort($summaries, fn($a, $b) => $b['probability'] <=> $a['probability']);

    return [
        'perGene' => $perGene,
        'summaries' => $summaries,
    ];
}

