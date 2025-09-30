<?php
function view(string $template, array $data = []): void
{
    if (!isset($data['currentRoute']) && isset($GLOBALS['currentRoute'])) {
        $data['currentRoute'] = $GLOBALS['currentRoute'];
    }

    extract($data);
    include __DIR__ . '/../public/views/' . $template . '.php';
}

function asset(string $path): string
{
    return BASE_URL . '/assets/' . ltrim($path, '/');
}

function redirect(string $route, array $params = []): void
{
    $query = http_build_query(array_merge(['route' => $route], $params));
    header('Location: ' . BASE_URL . '/index.php?' . $query);
    exit;
}

function current_user(): ?array
{
    return $_SESSION['user'] ?? null;
}

function require_login(): void
{
    if (!current_user()) {
        redirect('login');
    }
}

function is_authorized(string $capability): bool
{
    $user = current_user();
    if (!$user) {
        return false;
    }
    if ($user['role'] === 'admin') {
        return true;
    }

    return !empty($user[$capability]);
}

function flash(string $key, ?string $message = null): ?string
{
    if ($message === null) {
        if (isset($_SESSION['flash'][$key])) {
            $value = $_SESSION['flash'][$key];
            unset($_SESSION['flash'][$key]);
            return $value;
        }
        return null;
    }

    $_SESSION['flash'][$key] = $message;
    return null;
}

function ensure_directory(string $dir): void
{
    if (!is_dir($dir)) {
        mkdir($dir, 0775, true);
    }
}

function handle_upload(array $file): ?string
{
    if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
        return null;
    }

    ensure_directory(UPLOAD_PATH);
    $filename = bin2hex(random_bytes(8)) . '-' . preg_replace('/[^a-zA-Z0-9\.\-]/', '_', $file['name']);
    $destination = UPLOAD_PATH . '/' . $filename;
    if (!move_uploaded_file($file['tmp_name'], $destination)) {
        return null;
    }

    return 'uploads/' . $filename;
}

function get_setting(PDO $pdo, string $key, string $default = ''): string
{
    $stmt = $pdo->prepare('SELECT value FROM settings WHERE key = :key');
    $stmt->execute(['key' => $key]);
    $row = $stmt->fetch();
    return $row['value'] ?? $default;
}

function set_setting(PDO $pdo, string $key, string $value): void
{
    $stmt = $pdo->prepare('REPLACE INTO settings(key, value) VALUES (:key, :value)');
    $stmt->execute(['key' => $key, 'value' => $value]);
}
