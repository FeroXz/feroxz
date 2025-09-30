<?php

function get_gallery_items(PDO $pdo): array
{
    return $pdo->query('SELECT * FROM gallery_items ORDER BY created_at DESC')->fetchAll();
}

function get_gallery_item(PDO $pdo, int $id): ?array
{
    $stmt = $pdo->prepare('SELECT * FROM gallery_items WHERE id = :id');
    $stmt->execute(['id' => $id]);
    $item = $stmt->fetch();
    return $item ?: null;
}

function create_gallery_item(PDO $pdo, array $data): void
{
    $stmt = $pdo->prepare('INSERT INTO gallery_items(title, description, image_path) VALUES (:title, :description, :image_path)');
    $stmt->execute([
        'title' => $data['title'],
        'description' => $data['description'] ?? null,
        'image_path' => $data['image_path'],
    ]);
}

function update_gallery_item(PDO $pdo, int $id, array $data): void
{
    $stmt = $pdo->prepare('UPDATE gallery_items SET title = :title, description = :description, image_path = :image_path WHERE id = :id');
    $stmt->execute([
        'title' => $data['title'],
        'description' => $data['description'] ?? null,
        'image_path' => $data['image_path'],
        'id' => $id,
    ]);
}

function delete_gallery_item(PDO $pdo, int $id): void
{
    $stmt = $pdo->prepare('DELETE FROM gallery_items WHERE id = :id');
    $stmt->execute(['id' => $id]);
}

function ensure_default_gallery(PDO $pdo): void
{
    $count = (int)$pdo->query('SELECT COUNT(*) FROM gallery_items')->fetchColumn();
    if ($count > 0) {
        return;
    }

    $items = [
        [
            'title' => 'Solar Flair Terrarium',
            'description' => 'Bartagamen-Enclosure mit UVB-Gradienten, Mangrovenwurzel und Sukkulenten.',
            'image_path' => 'assets/demo/terrarium.svg'
        ],
        [
            'title' => 'Toffee Belly Showcase',
            'description' => 'Heterodon nasicus mit klar ausgeprägter Toffee Belly Zeichnung.',
            'image_path' => 'assets/demo/toffee-belly.svg'
        ],
    ];

    foreach ($items as $item) {
        create_gallery_item($pdo, $item);
    }
}

