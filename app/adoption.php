<?php
function create_listing(PDO $pdo, array $data): void
{
    $stmt = $pdo->prepare('INSERT INTO adoption_listings(animal_id, title, species, genetics, price, description, image_path, status, contact_email) VALUES (:animal_id, :title, :species, :genetics, :price, :description, :image_path, :status, :contact_email)');
    $stmt->execute([
        'animal_id' => $data['animal_id'] ?: null,
        'title' => $data['title'],
        'species' => $data['species'] ?? null,
        'genetics' => $data['genetics'] ?? null,
        'price' => $data['price'] ?? null,
        'description' => $data['description'] ?? null,
        'image_path' => $data['image_path'] ?? null,
        'status' => $data['status'] ?? 'available',
        'contact_email' => $data['contact_email'] ?? null,
    ]);
}

function update_listing(PDO $pdo, int $id, array $data): void
{
    $stmt = $pdo->prepare('UPDATE adoption_listings SET animal_id = :animal_id, title = :title, species = :species, genetics = :genetics, price = :price, description = :description, image_path = :image_path, status = :status, contact_email = :contact_email WHERE id = :id');
    $stmt->execute([
        'animal_id' => $data['animal_id'] ?: null,
        'title' => $data['title'],
        'species' => $data['species'] ?? null,
        'genetics' => $data['genetics'] ?? null,
        'price' => $data['price'] ?? null,
        'description' => $data['description'] ?? null,
        'image_path' => $data['image_path'] ?? null,
        'status' => $data['status'] ?? 'available',
        'contact_email' => $data['contact_email'] ?? null,
        'id' => $id,
    ]);
}

function delete_listing(PDO $pdo, int $id): void
{
    $stmt = $pdo->prepare('DELETE FROM adoption_listings WHERE id = :id');
    $stmt->execute(['id' => $id]);
}

function get_listing(PDO $pdo, int $id): ?array
{
    $stmt = $pdo->prepare('SELECT * FROM adoption_listings WHERE id = :id');
    $stmt->execute(['id' => $id]);
    $listing = $stmt->fetch();
    return $listing ?: null;
}

function get_listings(PDO $pdo): array
{
    return $pdo->query('SELECT * FROM adoption_listings ORDER BY created_at DESC')->fetchAll();
}

function get_public_listings(PDO $pdo): array
{
    $stmt = $pdo->query('SELECT * FROM adoption_listings WHERE status != "adopted" ORDER BY created_at DESC');
    return $stmt->fetchAll();
}

function create_inquiry(PDO $pdo, array $data): void
{
    $stmt = $pdo->prepare('INSERT INTO adoption_inquiries(listing_id, interested_in, sender_name, sender_email, message) VALUES (:listing_id, :interested_in, :sender_name, :sender_email, :message)');
    $stmt->execute([
        'listing_id' => $data['listing_id'],
        'interested_in' => $data['interested_in'] ?? null,
        'sender_name' => $data['sender_name'],
        'sender_email' => $data['sender_email'],
        'message' => $data['message'],
    ]);
}

function get_inquiries(PDO $pdo): array
{
    $sql = 'SELECT adoption_inquiries.*, adoption_listings.title as listing_title FROM adoption_inquiries JOIN adoption_listings ON adoption_listings.id = adoption_inquiries.listing_id ORDER BY adoption_inquiries.created_at DESC';
    return $pdo->query($sql)->fetchAll();
}
