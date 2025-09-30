<?php
function get_database_connection(): PDO
{
    static $pdo;
    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $needsDirectory = !is_dir(dirname(DATA_PATH));
    if ($needsDirectory) {
        mkdir(dirname(DATA_PATH), 0775, true);
    }

    $pdo = new PDO('sqlite:' . DATA_PATH);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    return $pdo;
}

function initialize_database(PDO $pdo): void
{
    $pdo->exec('CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        username TEXT UNIQUE NOT NULL,
        password_hash TEXT NOT NULL,
        role TEXT NOT NULL DEFAULT "admin",
        can_manage_animals INTEGER NOT NULL DEFAULT 1,
        can_manage_settings INTEGER NOT NULL DEFAULT 1,
        can_manage_adoptions INTEGER NOT NULL DEFAULT 1,
        created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP
    )');

    $pdo->exec('CREATE TABLE IF NOT EXISTS settings (
        key TEXT PRIMARY KEY,
        value TEXT NOT NULL
    )');

    $pdo->exec('CREATE TABLE IF NOT EXISTS animals (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL,
        species TEXT NOT NULL,
        age TEXT,
        genetics TEXT,
        origin TEXT,
        special_notes TEXT,
        description TEXT,
        image_path TEXT,
        owner_id INTEGER,
        is_private INTEGER NOT NULL DEFAULT 0,
        is_showcased INTEGER NOT NULL DEFAULT 0,
        created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY(owner_id) REFERENCES users(id)
    )');

    $pdo->exec('CREATE TABLE IF NOT EXISTS adoption_listings (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        animal_id INTEGER,
        title TEXT NOT NULL,
        species TEXT,
        genetics TEXT,
        price TEXT,
        description TEXT,
        image_path TEXT,
        status TEXT NOT NULL DEFAULT "available",
        contact_email TEXT,
        created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY(animal_id) REFERENCES animals(id)
    )');

    $pdo->exec('CREATE TABLE IF NOT EXISTS adoption_inquiries (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        listing_id INTEGER NOT NULL,
        interested_in TEXT,
        sender_name TEXT NOT NULL,
        sender_email TEXT NOT NULL,
        message TEXT NOT NULL,
        created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY(listing_id) REFERENCES adoption_listings(id)
    )');
}
