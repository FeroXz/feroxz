<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/database.php';
require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/settings.php';
require_once __DIR__ . '/animals.php';
require_once __DIR__ . '/adoption.php';
require_once __DIR__ . '/users.php';

$pdo = get_database_connection();
initialize_database($pdo);
ensure_default_admin($pdo);
ensure_default_settings($pdo);
