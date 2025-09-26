<?php

declare(strict_types=1);

if (!function_exists('str_starts_with')) {
    function str_starts_with(string $haystack, string $needle): bool
    {
        return $needle === '' || strncmp($haystack, $needle, strlen($needle)) === 0;
    }
}

if (!function_exists('str_ends_with')) {
    function str_ends_with(string $haystack, string $needle): bool
    {
        if ($needle === '') {
            return true;
        }

        return substr($haystack, -strlen($needle)) === $needle;
    }
}

session_start();

$baseDir = dirname(__DIR__);
$databaseFile = $baseDir . '/cms.db';
$uploadDir = $baseDir . '/static/uploads';

if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0775, true);
}

if (!is_writable($uploadDir)) {
    @chmod($uploadDir, 0775);
}

$pdo = new PDO('sqlite:' . $databaseFile);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

initializeDatabase($pdo);

$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/';
$path = '/' . trim($path, '/');
if ($path !== '/' && str_ends_with($path, '/')) {
    $path = rtrim($path, '/');
}
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

if ($path === '/') {
    $posts = fetchAll($pdo, 'SELECT id, title, content, created_at, updated_at FROM posts ORDER BY created_at DESC');
    render('home', [
        'pageTitle' => 'Startseite - Feroxz CMS',
        'posts' => array_map(static fn ($post) => formatTimestamps($post), $posts),
    ]);
    exit;
}

if ($path === '/gallery') {
    $items = fetchAll($pdo, 'SELECT id, title, description, filename, created_at FROM gallery ORDER BY created_at DESC');
    render('gallery', [
        'pageTitle' => 'Galerie - Feroxz CMS',
        'items' => $items,
    ]);
    exit;
}

if (preg_match('#^/page/([a-z0-9\-]+)$#i', $path, $matches)) {
    $slug = $matches[1];
    $page = fetchOne($pdo, 'SELECT id, title, content FROM pages WHERE slug = :slug', ['slug' => $slug]);
    if (!$page) {
        render404();
    } else {
        render('page', [
            'pageTitle' => $page['title'] . ' - Feroxz CMS',
            'page' => $page,
        ]);
    }
    exit;
}

if ($path === '/admin/login') {
    if ($method === 'POST') {
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        $user = fetchOne($pdo, 'SELECT id, username, password_hash FROM admins WHERE username = :username', [
            'username' => $username,
        ]);
        if ($user && password_verify($password, $user['password_hash'])) {
            $_SESSION['admin'] = ['id' => $user['id'], 'username' => $user['username']];
            flash('Willkommen zurück!', 'success');
            redirect('/admin');
        }
        flash('Ungültige Zugangsdaten.', 'danger');
    }
    render('admin/login', ['pageTitle' => 'Login - Feroxz CMS']);
    exit;
}

if ($path === '/admin/logout') {
    session_destroy();
    session_start();
    flash('Du wurdest abgemeldet.', 'info');
    redirect('/');
}

if (str_starts_with($path, '/admin')) {
    requireAdmin();
}

if ($path === '/admin' && $method === 'GET') {
    $posts = fetchAll($pdo, 'SELECT id, title, created_at, updated_at FROM posts ORDER BY created_at DESC');
    $pages = fetchAll($pdo, 'SELECT id, title, slug FROM pages ORDER BY title ASC');
    $gallery = fetchAll($pdo, 'SELECT id, title, filename FROM gallery ORDER BY created_at DESC');
    render('admin/dashboard', [
        'pageTitle' => 'Adminbereich - Feroxz CMS',
        'posts' => array_map(static fn ($post) => formatTimestamps($post), $posts),
        'pages' => $pages,
        'gallery' => $gallery,
    ]);
    exit;
}

if ($path === '/admin/posts/new') {
    if ($method === 'POST') {
        $title = trim($_POST['title'] ?? '');
        $content = trim($_POST['content'] ?? '');
        if ($title === '' || $content === '') {
            flash('Titel und Inhalt dürfen nicht leer sein.', 'warning');
        } else {
            $now = currentTimestamp();
            $stmt = $pdo->prepare('INSERT INTO posts (title, content, created_at, updated_at) VALUES (:title, :content, :created_at, :updated_at)');
            $stmt->execute([
                'title' => $title,
                'content' => $content,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
            flash('Beitrag wurde erstellt.', 'success');
            redirect('/admin');
        }
    }
    render('admin/post_form', [
        'pageTitle' => 'Neuen Beitrag erstellen - Feroxz CMS',
        'heading' => 'Neuen Beitrag erstellen',
        'post' => null,
    ]);
    exit;
}

if (preg_match('#^/admin/posts/(\d+)/edit$#', $path, $matches)) {
    $postId = (int) $matches[1];
    $post = fetchOne($pdo, 'SELECT id, title, content FROM posts WHERE id = :id', ['id' => $postId]);
    if (!$post) {
        flash('Beitrag wurde nicht gefunden.', 'danger');
        redirect('/admin');
    }
    if ($method === 'POST') {
        $title = trim($_POST['title'] ?? '');
        $content = trim($_POST['content'] ?? '');
        if ($title === '' || $content === '') {
            flash('Titel und Inhalt dürfen nicht leer sein.', 'warning');
        } else {
            $stmt = $pdo->prepare('UPDATE posts SET title = :title, content = :content, updated_at = :updated_at WHERE id = :id');
            $stmt->execute([
                'title' => $title,
                'content' => $content,
                'updated_at' => currentTimestamp(),
                'id' => $postId,
            ]);
            flash('Beitrag wurde aktualisiert.', 'success');
            redirect('/admin');
        }
    }
    render('admin/post_form', [
        'pageTitle' => 'Beitrag bearbeiten - Feroxz CMS',
        'heading' => 'Beitrag bearbeiten',
        'post' => $post,
    ]);
    exit;
}

if (preg_match('#^/admin/posts/(\d+)/delete$#', $path, $matches) && $method === 'POST') {
    $postId = (int) $matches[1];
    $stmt = $pdo->prepare('DELETE FROM posts WHERE id = :id');
    $stmt->execute(['id' => $postId]);
    flash('Beitrag wurde gelöscht.', 'info');
    redirect('/admin');
}

if ($path === '/admin/pages/new') {
    if ($method === 'POST') {
        $title = trim($_POST['title'] ?? '');
        $slug = sanitizeSlug($_POST['slug'] ?? '');
        $content = trim($_POST['content'] ?? '');
        if ($title === '' || $slug === '' || $content === '') {
            flash('Titel, Slug und Inhalt dürfen nicht leer sein.', 'warning');
        } else {
            try {
                $now = currentTimestamp();
                $stmt = $pdo->prepare('INSERT INTO pages (title, slug, content, created_at, updated_at) VALUES (:title, :slug, :content, :created_at, :updated_at)');
                $stmt->execute([
                    'title' => $title,
                    'slug' => $slug,
                    'content' => $content,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
                flash('Seite wurde erstellt.', 'success');
                redirect('/admin');
            } catch (PDOException $e) {
                flash('Slug ist bereits vergeben.', 'danger');
            }
        }
    }
    render('admin/page_form', [
        'pageTitle' => 'Neue Seite erstellen - Feroxz CMS',
        'heading' => 'Neue Seite erstellen',
        'page' => null,
    ]);
    exit;
}

if (preg_match('#^/admin/pages/(\d+)/edit$#', $path, $matches)) {
    $pageId = (int) $matches[1];
    $page = fetchOne($pdo, 'SELECT id, title, slug, content FROM pages WHERE id = :id', ['id' => $pageId]);
    if (!$page) {
        flash('Seite wurde nicht gefunden.', 'danger');
        redirect('/admin');
    }
    if ($method === 'POST') {
        $title = trim($_POST['title'] ?? '');
        $slug = sanitizeSlug($_POST['slug'] ?? '');
        $content = trim($_POST['content'] ?? '');
        if ($title === '' || $slug === '' || $content === '') {
            flash('Titel, Slug und Inhalt dürfen nicht leer sein.', 'warning');
        } else {
            try {
                $stmt = $pdo->prepare('UPDATE pages SET title = :title, slug = :slug, content = :content, updated_at = :updated_at WHERE id = :id');
                $stmt->execute([
                    'title' => $title,
                    'slug' => $slug,
                    'content' => $content,
                    'updated_at' => currentTimestamp(),
                    'id' => $pageId,
                ]);
                flash('Seite wurde aktualisiert.', 'success');
                redirect('/admin');
            } catch (PDOException $e) {
                flash('Slug ist bereits vergeben.', 'danger');
            }
        }
    }
    render('admin/page_form', [
        'pageTitle' => 'Seite bearbeiten - Feroxz CMS',
        'heading' => 'Seite bearbeiten',
        'page' => $page,
    ]);
    exit;
}

if (preg_match('#^/admin/pages/(\d+)/delete$#', $path, $matches) && $method === 'POST') {
    $pageId = (int) $matches[1];
    $stmt = $pdo->prepare('DELETE FROM pages WHERE id = :id');
    $stmt->execute(['id' => $pageId]);
    flash('Seite wurde gelöscht.', 'info');
    redirect('/admin');
}

if ($path === '/admin/gallery/new') {
    if ($method === 'POST') {
        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $filename = handleUpload($_FILES['file'] ?? null, $uploadDir);
        if ($title === '' || $filename === null) {
            if ($filename === null) {
                flash('Datei-Upload fehlgeschlagen.', 'danger');
            } else {
                flash('Titel darf nicht leer sein.', 'warning');
            }
        } else {
            $stmt = $pdo->prepare('INSERT INTO gallery (title, description, filename, created_at, updated_at) VALUES (:title, :description, :filename, :created_at, :updated_at)');
            $stmt->execute([
                'title' => $title,
                'description' => $description,
                'filename' => $filename,
                'created_at' => currentTimestamp(),
                'updated_at' => currentTimestamp(),
            ]);
            flash('Galerie-Eintrag wurde erstellt.', 'success');
            redirect('/admin');
        }
    }
    render('admin/gallery_form', [
        'pageTitle' => 'Neuen Galerie-Eintrag erstellen - Feroxz CMS',
        'heading' => 'Neuen Galerie-Eintrag erstellen',
        'item' => null,
    ]);
    exit;
}

if (preg_match('#^/admin/gallery/(\d+)/edit$#', $path, $matches)) {
    $itemId = (int) $matches[1];
    $item = fetchOne($pdo, 'SELECT id, title, description, filename FROM gallery WHERE id = :id', ['id' => $itemId]);
    if (!$item) {
        flash('Galerie-Eintrag wurde nicht gefunden.', 'danger');
        redirect('/admin');
    }
    if ($method === 'POST') {
        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $uploaded = handleUpload($_FILES['file'] ?? null, $uploadDir, false);
        $filename = $uploaded ?: $item['filename'];
        if ($title === '') {
            flash('Titel darf nicht leer sein.', 'warning');
        } else {
            if ($uploaded && $item['filename'] && is_file($uploadDir . '/' . $item['filename'])) {
                @unlink($uploadDir . '/' . $item['filename']);
            }
            $stmt = $pdo->prepare('UPDATE gallery SET title = :title, description = :description, filename = :filename, updated_at = :updated_at WHERE id = :id');
            $stmt->execute([
                'title' => $title,
                'description' => $description,
                'filename' => $filename,
                'updated_at' => currentTimestamp(),
                'id' => $itemId,
            ]);
            flash('Galerie-Eintrag wurde aktualisiert.', 'success');
            redirect('/admin');
        }
    }
    render('admin/gallery_form', [
        'pageTitle' => 'Galerie-Eintrag bearbeiten - Feroxz CMS',
        'heading' => 'Galerie-Eintrag bearbeiten',
        'item' => $item,
    ]);
    exit;
}

if (preg_match('#^/admin/gallery/(\d+)/delete$#', $path, $matches) && $method === 'POST') {
    $itemId = (int) $matches[1];
    $item = fetchOne($pdo, 'SELECT filename FROM gallery WHERE id = :id', ['id' => $itemId]);
    $stmt = $pdo->prepare('DELETE FROM gallery WHERE id = :id');
    $stmt->execute(['id' => $itemId]);
    if ($item && $item['filename'] && is_file($uploadDir . '/' . $item['filename'])) {
        @unlink($uploadDir . '/' . $item['filename']);
    }
    flash('Galerie-Eintrag wurde gelöscht.', 'info');
    redirect('/admin');
}

render404();

function render(string $template, array $data = []): void
{
    $currentYear = (int) date('Y');
    $flashMessages = $_SESSION['flashes'] ?? [];
    unset($_SESSION['flashes']);

    extract($data);

    ob_start();
    include __DIR__ . '/views/' . $template . '.php';
    $content = ob_get_clean();

    include __DIR__ . '/views/layout.php';
}

function render404(): void
{
    http_response_code(404);
    render('404', ['pageTitle' => 'Seite nicht gefunden - Feroxz CMS']);
}

function redirect(string $location): void
{
    header('Location: ' . $location);
    exit;
}

function flash(string $message, string $type = 'info'): void
{
    if (!isset($_SESSION['flashes']) || !is_array($_SESSION['flashes'])) {
        $_SESSION['flashes'] = [];
    }

    $_SESSION['flashes'][] = ['message' => $message, 'type' => $type];
}

function requireAdmin(): void
{
    if (empty($_SESSION['admin'])) {
        flash('Bitte melde dich zuerst an.', 'warning');
        redirect('/admin/login');
    }
}

function currentTimestamp(): string
{
    return date('Y-m-d H:i:s');
}

function fetchAll(PDO $pdo, string $sql, array $params = []): array
{
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

function fetchOne(PDO $pdo, string $sql, array $params = []): ?array
{
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $result = $stmt->fetch();
    return $result === false ? null : $result;
}

function formatTimestamps(array $record): array
{
    foreach (['created_at', 'updated_at'] as $key) {
        if (!empty($record[$key])) {
            $record[$key] = str_replace('T', ' ', (string) $record[$key]);
        }
    }
    return $record;
}

function sanitizeSlug(string $slug): string
{
    $slug = strtolower(trim($slug));
    $slug = preg_replace('/[^a-z0-9\-]+/i', '-', $slug) ?? '';
    return trim($slug, '-');
}

function handleUpload(?array $file, string $uploadDir, bool $requireFile = true): ?string
{
    if (!$file || ($file['error'] === UPLOAD_ERR_NO_FILE && !$requireFile)) {
        return null;
    }

    if (!isset($file['error']) || $file['error'] !== UPLOAD_ERR_OK) {
        return $requireFile ? null : null;
    }

    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $extension = $extension ? '.' . preg_replace('/[^a-zA-Z0-9]/', '', $extension) : '';
    $filename = uniqid('upload_', true) . $extension;
    $destination = rtrim($uploadDir, '/') . '/' . $filename;

    if (!move_uploaded_file($file['tmp_name'], $destination)) {
        return null;
    }

    return $filename;
}

function initializeDatabase(PDO $pdo): void
{
    $pdo->exec('CREATE TABLE IF NOT EXISTS posts (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        title TEXT NOT NULL,
        content TEXT NOT NULL,
        created_at TEXT NOT NULL,
        updated_at TEXT NOT NULL
    )');

    $pdo->exec('CREATE TABLE IF NOT EXISTS pages (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        title TEXT NOT NULL,
        slug TEXT NOT NULL UNIQUE,
        content TEXT NOT NULL,
        created_at TEXT NOT NULL,
        updated_at TEXT NOT NULL
    )');

    $pdo->exec('CREATE TABLE IF NOT EXISTS gallery (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        title TEXT NOT NULL,
        description TEXT,
        filename TEXT NOT NULL,
        created_at TEXT NOT NULL,
        updated_at TEXT NOT NULL
    )');

    $pdo->exec('CREATE TABLE IF NOT EXISTS admins (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        username TEXT NOT NULL UNIQUE,
        password_hash TEXT NOT NULL
    )');

    $defaultUsername = getenv('CMS_ADMIN_USERNAME') ?: 'admin';
    $defaultPassword = getenv('CMS_ADMIN_PASSWORD') ?: 'changeme';

    $stmt = $pdo->prepare('SELECT id FROM admins WHERE username = :username');
    $stmt->execute(['username' => $defaultUsername]);
    if (!$stmt->fetch()) {
        $hash = password_hash($defaultPassword, PASSWORD_DEFAULT);
        $insert = $pdo->prepare('INSERT INTO admins (username, password_hash) VALUES (:username, :password_hash)');
        $insert->execute([
            'username' => $defaultUsername,
            'password_hash' => $hash,
        ]);
    }
}
