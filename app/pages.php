<?php
function create_page(PDO $pdo, array $data): int
{
    $slug = $data['slug'] ?: slugify($data['title']);
    $slug = ensure_unique_slug($pdo, 'pages', $slug);
    $stmt = $pdo->prepare('INSERT INTO pages(title, slug, content, is_published) VALUES (:title, :slug, :content, :is_published)');
    $stmt->execute([
        'title' => $data['title'],
        'slug' => $slug,
        'content' => $data['content'],
        'is_published' => !empty($data['is_published']) ? 1 : 0,
    ]);
    return (int)$pdo->lastInsertId();
}

function update_page(PDO $pdo, int $id, array $data): void
{
    $slug = $data['slug'] ?: slugify($data['title']);
    $slug = ensure_unique_slug($pdo, 'pages', $slug, $id);
    $stmt = $pdo->prepare('UPDATE pages SET title = :title, slug = :slug, content = :content, is_published = :is_published, updated_at = CURRENT_TIMESTAMP WHERE id = :id');
    $stmt->execute([
        'title' => $data['title'],
        'slug' => $slug,
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

function get_page(PDO $pdo, int $id): ?array
{
    $stmt = $pdo->prepare('SELECT * FROM pages WHERE id = :id');
    $stmt->execute(['id' => $id]);
    $page = $stmt->fetch();
    return $page ?: null;
}

function get_page_by_slug(PDO $pdo, string $slug): ?array
{
    $stmt = $pdo->prepare('SELECT * FROM pages WHERE slug = :slug');
    $stmt->execute(['slug' => $slug]);
    $page = $stmt->fetch();
    return $page ?: null;
}

function get_pages(PDO $pdo): array
{
    return $pdo->query('SELECT * FROM pages ORDER BY created_at DESC')->fetchAll();
}

function get_published_pages(PDO $pdo): array
{
    return $pdo->query('SELECT * FROM pages WHERE is_published = 1 ORDER BY title ASC')->fetchAll();
}

