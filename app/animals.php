<?php
function create_animal(PDO $pdo, array $data): void
{
    $stmt = $pdo->prepare('INSERT INTO animals(name, species, age, genetics, genetics_profile, origin, special_notes, description, image_path, owner_id, is_private, is_showcased) VALUES (:name, :species, :age, :genetics, :genetics_profile, :origin, :special_notes, :description, :image_path, :owner_id, :is_private, :is_showcased)');
    $stmt->execute([
        'name' => $data['name'],
        'species' => $data['species'],
        'age' => $data['age'] ?? null,
        'genetics' => $data['genetics'] ?? null,
        'genetics_profile' => $data['genetics_profile'] ?? null,
        'origin' => $data['origin'] ?? null,
        'special_notes' => $data['special_notes'] ?? null,
        'description' => $data['description'] ?? null,
        'image_path' => $data['image_path'] ?? null,
        'owner_id' => $data['owner_id'] ?: null,
        'is_private' => !empty($data['is_private']) ? 1 : 0,
        'is_showcased' => !empty($data['is_showcased']) ? 1 : 0,
    ]);
}

function update_animal(PDO $pdo, int $id, array $data): void
{
    $stmt = $pdo->prepare('UPDATE animals SET name = :name, species = :species, age = :age, genetics = :genetics, genetics_profile = :genetics_profile, origin = :origin, special_notes = :special_notes, description = :description, image_path = :image_path, owner_id = :owner_id, is_private = :is_private, is_showcased = :is_showcased WHERE id = :id');
    $stmt->execute([
        'name' => $data['name'],
        'species' => $data['species'],
        'age' => $data['age'] ?? null,
        'genetics' => $data['genetics'] ?? null,
        'genetics_profile' => $data['genetics_profile'] ?? null,
        'origin' => $data['origin'] ?? null,
        'special_notes' => $data['special_notes'] ?? null,
        'description' => $data['description'] ?? null,
        'image_path' => $data['image_path'] ?? null,
        'owner_id' => $data['owner_id'] ?: null,
        'is_private' => !empty($data['is_private']) ? 1 : 0,
        'is_showcased' => !empty($data['is_showcased']) ? 1 : 0,
        'id' => $id
    ]);
}

function delete_animal(PDO $pdo, int $id): void
{
    $stmt = $pdo->prepare('DELETE FROM animals WHERE id = :id');
    $stmt->execute(['id' => $id]);
}

function get_animal(PDO $pdo, int $id): ?array
{
    $stmt = $pdo->prepare('SELECT * FROM animals WHERE id = :id');
    $stmt->execute(['id' => $id]);
    $animal = $stmt->fetch();
    return $animal ?: null;
}

function get_animals(PDO $pdo): array
{
    return $pdo->query('SELECT animals.*, users.username as owner_name FROM animals LEFT JOIN users ON users.id = animals.owner_id ORDER BY created_at DESC')->fetchAll();
}

function get_showcased_animals(PDO $pdo): array
{
    $stmt = $pdo->query('SELECT * FROM animals WHERE is_showcased = 1 AND (is_private = 0) ORDER BY created_at DESC');
    return $stmt->fetchAll();
}

function get_user_animals(PDO $pdo, int $userId): array
{
    $stmt = $pdo->prepare('SELECT * FROM animals WHERE owner_id = :owner ORDER BY created_at DESC');
    $stmt->execute(['owner' => $userId]);
    return $stmt->fetchAll();
}

function get_public_animals(PDO $pdo): array
{
    return $pdo->query('SELECT * FROM animals WHERE is_private = 0 ORDER BY created_at DESC')->fetchAll();
}

function decode_genetics_profile(?string $profile): array
{
    if (!$profile) {
        return [];
    }

    $decoded = json_decode($profile, true);
    if (is_array($decoded)) {
        return array_values(array_filter($decoded, fn($value) => is_string($value) && $value !== ''));
    }

    return array_values(array_filter(array_map('trim', explode(',', (string)$profile))));
}
