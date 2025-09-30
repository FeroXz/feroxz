<?php

function ensure_default_pages(PDO $pdo): void
{
    $defaults = [
        [
            'title' => 'Über FeroxZ',
            'slug' => 'ueber-feroxz',
            'excerpt' => 'Unsere Mission für verantwortungsvolle Reptilienhaltung.',
            'content' => '<h2>Unsere Mission</h2><p>FeroxZ steht für eine verantwortungsvolle und moderne Haltung von Bartagamen (Pogona vitticeps) und Hakennasennattern (Heterodon nasicus). Wir begleiten Halter*innen von der Erstinformation über die Auswahl genetischer Linien bis hin zu Gesundheits- und Ernährungsfragen.</p><h2>Warum wir das tun</h2><p>Die Bedürfnisse von Reptilien unterscheiden sich stark von klassischen Haustieren. Mit über zehn Jahren Erfahrung in Zucht und Pflege vermitteln wir praxisnahes Wissen, unterstützen bei der Einrichtung optimaler Lebensräume und stellen aktuelle Forschungsergebnisse bereit.</p>'
        ],
        [
            'title' => 'Beratung & Workshops',
            'slug' => 'beratung-workshops',
            'excerpt' => 'Individuelle Beratungen, Online-Seminare und Vor-Ort-Coachings.',
            'content' => '<h2>Individuelle Beratung</h2><p>Ob Terrarium-Setup, Auswahl der passenden Genetik oder Ernährungsplan: Wir begleiten dich Schritt für Schritt.</p><h2>Workshops</h2><p>Regelmäßige Workshops zu Themen wie Quarantäne-Management, Parasitenkontrolle und Insektenzucht helfen dir, dein Wissen zu vertiefen.</p>'
        ],
    ];

    foreach ($defaults as $page) {
        $stmt = $pdo->prepare('INSERT OR IGNORE INTO pages(title, slug, excerpt, content, is_published) VALUES (:title, :slug, :excerpt, :content, 1)');
        $stmt->execute($page);
    }
}

function get_pages(PDO $pdo): array
{
    return $pdo->query('SELECT * FROM pages ORDER BY created_at DESC')->fetchAll();
}

function get_published_pages(PDO $pdo): array
{
    return $pdo->query('SELECT title, slug, excerpt FROM pages WHERE is_published = 1 ORDER BY created_at DESC')->fetchAll();
}

function get_page(PDO $pdo, int $id): ?array
{
    $stmt = $pdo->prepare('SELECT * FROM pages WHERE id = :id');
    $stmt->execute(['id' => $id]);
    $page = $stmt->fetch();
    return $page ?: null;
}

function get_page_by_slug(PDO $pdo, string $slug): ?array
{
    $stmt = $pdo->prepare('SELECT * FROM pages WHERE slug = :slug AND is_published = 1');
    $stmt->execute(['slug' => $slug]);
    $page = $stmt->fetch();
    return $page ?: null;
}

function create_page(PDO $pdo, array $data): void
{
    $stmt = $pdo->prepare('INSERT INTO pages(title, slug, excerpt, content, is_published) VALUES (:title, :slug, :excerpt, :content, :is_published)');
    $stmt->execute([
        'title' => $data['title'],
        'slug' => $data['slug'],
        'excerpt' => $data['excerpt'] ?? null,
        'content' => $data['content'],
        'is_published' => !empty($data['is_published']) ? 1 : 0,
    ]);
}

function update_page(PDO $pdo, int $id, array $data): void
{
    $stmt = $pdo->prepare('UPDATE pages SET title = :title, slug = :slug, excerpt = :excerpt, content = :content, is_published = :is_published, updated_at = CURRENT_TIMESTAMP WHERE id = :id');
    $stmt->execute([
        'title' => $data['title'],
        'slug' => $data['slug'],
        'excerpt' => $data['excerpt'] ?? null,
        'content' => $data['content'],
        'is_published' => !empty($data['is_published']) ? 1 : 0,
        'id' => $id,
    ]);
}

function delete_page(PDO $pdo, int $id): void
{
    $stmt = $pdo->prepare('DELETE FROM pages WHERE id = :id');
    $stmt->execute(['id' => $id]);
}

