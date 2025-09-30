<?php

function ensure_default_posts(PDO $pdo): void
{
    $exists = (int)$pdo->query('SELECT COUNT(*) FROM posts')->fetchColumn();
    if ($exists > 0) {
        return;
    }

    $intro = '<p>Willkommen im FeroxZ Journal. Hier teilen wir Erfahrungsberichte aus der Praxis, stellen neue Terrarien-Setups vor und geben Updates zu spannenden Zuchtprojekten rund um Bartagamen und Hakennasennattern.</p>';
    create_post($pdo, [
        'title' => 'Willkommen im FeroxZ Journal',
        'slug' => 'willkommen-im-feroxz-journal',
        'excerpt' => 'Neuigkeiten, Fachwissen und Hintergrundberichte aus unserem Reptilienzentrum.',
        'content' => $intro,
        'is_published' => 1,
    ]);
}

function get_posts(PDO $pdo): array
{
    return $pdo->query('SELECT * FROM posts ORDER BY COALESCE(published_at, created_at) DESC')->fetchAll();
}

function get_published_posts(PDO $pdo): array
{
    return $pdo->query('SELECT * FROM posts WHERE published_at IS NOT NULL ORDER BY published_at DESC')->fetchAll();
}

function get_post(PDO $pdo, int $id): ?array
{
    $stmt = $pdo->prepare('SELECT * FROM posts WHERE id = :id');
    $stmt->execute(['id' => $id]);
    $post = $stmt->fetch();
    return $post ?: null;
}

function get_post_by_slug(PDO $pdo, string $slug): ?array
{
    $stmt = $pdo->prepare('SELECT * FROM posts WHERE slug = :slug AND published_at IS NOT NULL');
    $stmt->execute(['slug' => $slug]);
    $post = $stmt->fetch();
    return $post ?: null;
}

function create_post(PDO $pdo, array $data): void
{
    $stmt = $pdo->prepare('INSERT INTO posts(title, slug, excerpt, content, published_at) VALUES (:title, :slug, :excerpt, :content, :published_at)');
    $stmt->execute([
        'title' => $data['title'],
        'slug' => $data['slug'],
        'excerpt' => $data['excerpt'] ?? null,
        'content' => $data['content'],
        'published_at' => !empty($data['is_published']) ? ($data['published_at'] ?? date('c')) : null,
    ]);
}

function update_post(PDO $pdo, int $id, array $data): void
{
    $stmt = $pdo->prepare('UPDATE posts SET title = :title, slug = :slug, excerpt = :excerpt, content = :content, published_at = :published_at, updated_at = CURRENT_TIMESTAMP WHERE id = :id');
    $stmt->execute([
        'title' => $data['title'],
        'slug' => $data['slug'],
        'excerpt' => $data['excerpt'] ?? null,
        'content' => $data['content'],
        'published_at' => !empty($data['is_published']) ? ($data['published_at'] ?? date('c')) : null,
        'id' => $id,
    ]);
}

function delete_post(PDO $pdo, int $id): void
{
    $stmt = $pdo->prepare('DELETE FROM posts WHERE id = :id');
    $stmt->execute(['id' => $id]);
}

