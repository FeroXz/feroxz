<?php

function ensure_default_menu(PDO $pdo): void
{
    $count = (int)$pdo->query('SELECT COUNT(*) FROM menu_items')->fetchColumn();
    if ($count > 0) {
        return;
    }

    $items = [
        ['label' => 'Start', 'route' => 'home', 'position' => 0],
        ['label' => 'Tiere', 'route' => 'animals', 'position' => 1],
        ['label' => 'Tierabgabe', 'route' => 'adoption', 'position' => 2],
        ['label' => 'Pflegeleitfaden', 'route' => 'care-guides', 'position' => 3],
        ['label' => 'Genetik', 'route' => 'genetics', 'position' => 4],
        ['label' => 'Galerie', 'route' => 'gallery', 'position' => 5],
        ['label' => 'Journal', 'route' => 'blog', 'position' => 6],
    ];

    $stmt = $pdo->prepare('INSERT INTO menu_items(label, route, position, is_visible) VALUES (:label, :route, :position, 1)');
    foreach ($items as $item) {
        $stmt->execute($item);
    }
}

function get_menu_items(PDO $pdo): array
{
    return $pdo->query('SELECT * FROM menu_items ORDER BY position ASC, created_at ASC')->fetchAll();
}

function get_visible_menu(PDO $pdo): array
{
    $stmt = $pdo->query('SELECT * FROM menu_items WHERE is_visible = 1 ORDER BY position ASC, created_at ASC');
    return $stmt->fetchAll();
}

function create_menu_item(PDO $pdo, array $data): void
{
    $stmt = $pdo->prepare('INSERT INTO menu_items(label, route, page_slug, external_url, is_visible, position) VALUES (:label, :route, :page_slug, :external_url, :is_visible, :position)');
    $stmt->execute([
        'label' => $data['label'],
        'route' => $data['route'] ?? null,
        'page_slug' => $data['page_slug'] ?? null,
        'external_url' => $data['external_url'] ?? null,
        'is_visible' => !empty($data['is_visible']) ? 1 : 0,
        'position' => (int)($data['position'] ?? 0),
    ]);
}

function update_menu_item(PDO $pdo, int $id, array $data): void
{
    $stmt = $pdo->prepare('UPDATE menu_items SET label = :label, route = :route, page_slug = :page_slug, external_url = :external_url, is_visible = :is_visible, position = :position WHERE id = :id');
    $stmt->execute([
        'label' => $data['label'],
        'route' => $data['route'] ?? null,
        'page_slug' => $data['page_slug'] ?? null,
        'external_url' => $data['external_url'] ?? null,
        'is_visible' => !empty($data['is_visible']) ? 1 : 0,
        'position' => (int)($data['position'] ?? 0),
        'id' => $id,
    ]);
}

function delete_menu_item(PDO $pdo, int $id): void
{
    $stmt = $pdo->prepare('DELETE FROM menu_items WHERE id = :id');
    $stmt->execute(['id' => $id]);
}

