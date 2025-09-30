<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/database.php';
require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/settings.php';
require_once __DIR__ . '/animals.php';
require_once __DIR__ . '/adoption.php';
require_once __DIR__ . '/users.php';
require_once __DIR__ . '/pages.php';
require_once __DIR__ . '/posts.php';
require_once __DIR__ . '/gallery.php';
require_once __DIR__ . '/menu.php';
require_once __DIR__ . '/care_guides.php';
require_once __DIR__ . '/genetics.php';

$pdo = get_database_connection();
initialize_database($pdo);
ensure_default_admin($pdo);
ensure_default_settings($pdo);
ensure_default_pages($pdo);
ensure_default_posts($pdo);
ensure_default_gallery($pdo);
ensure_default_menu($pdo);
ensure_default_care_guides($pdo);
ensure_default_genetics($pdo);
