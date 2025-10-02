<?php
function view(string $template, array $data = []): void
{
    if (!isset($data['currentRoute']) && isset($GLOBALS['currentRoute'])) {
        $data['currentRoute'] = $GLOBALS['currentRoute'];
    }

    global $pdo;
    if (isset($pdo)) {
        if (!isset($data['navPages']) && function_exists('get_navigation_pages')) {
            $data['navPages'] = get_navigation_pages($pdo);
        }
        if (!isset($data['navCareArticles']) && function_exists('get_published_care_articles')) {
            $data['navCareArticles'] = get_published_care_articles($pdo);
        }
        if (!isset($data['settings']) && function_exists('get_all_settings')) {
            $data['settings'] = get_all_settings($pdo);
        }
    }

    if (!isset($data['pageMeta'])) {
        $data['pageMeta'] = build_page_meta([], $data['settings'] ?? []);
    } else {
        $data['pageMeta'] = build_page_meta($data['pageMeta'], $data['settings'] ?? []);
    }

    extract($data);
    include __DIR__ . '/../public/views/' . $template . '.php';
}

function asset(string $path): string
{
    $assetHost = getenv('ASSET_CDN_URL');
    $prefix = $assetHost ? rtrim($assetHost, '/') : BASE_URL;
    return $prefix . '/assets/' . ltrim($path, '/');
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

function canonical_url(string $path = ''): string
{
    $cleanPath = '/' . ltrim($path ?: parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/', '/');
    return 'https://' . SITE_DOMAIN . $cleanPath;
}

function absolute_url(string $path = ''): string
{
    $cleanPath = '/' . ltrim($path, '/');
    return 'https://' . SITE_DOMAIN . $cleanPath;
}

function get_gender_options(): array
{
    return [
        'female' => ['label' => 'Weiblich', 'icon' => '♀'],
        'male' => ['label' => 'Männlich', 'icon' => '♂'],
        'unknown' => ['label' => 'Unbekannt', 'icon' => '⚲'],
    ];
}

function normalize_sex(?string $value): string
{
    $normalized = strtolower(trim((string)$value));
    $options = get_gender_options();
    return array_key_exists($normalized, $options) ? $normalized : 'unknown';
}

function render_gender_field(string $name, ?string $value = null, array $options = []): string
{
    $legend = $options['legend'] ?? 'Geschlecht';
    $idBase = preg_replace('/[^a-z0-9_-]/i', '_', $options['id_base'] ?? $name);
    $required = !empty($options['required']) ? ' required' : '';
    $current = normalize_sex($value);
    $fieldsetClasses = trim('gender-field ' . ($options['class'] ?? ''));
    $choiceClass = trim('gender-choice ' . ($options['choice_class'] ?? ''));

    $html = '<fieldset class="' . htmlspecialchars($fieldsetClasses, ENT_QUOTES) . '">';
    $html .= '<legend>' . htmlspecialchars($legend, ENT_QUOTES) . '</legend>';
    $html .= '<div class="gender-options">';

    foreach (get_gender_options() as $key => $meta) {
        $id = $idBase . '-' . $key;
        $checked = $current === $key ? ' checked' : '';
        $html .= '<label class="' . htmlspecialchars($choiceClass, ENT_QUOTES) . '">';
        $html .= '<input class="sr-only" type="radio" id="' . htmlspecialchars($id, ENT_QUOTES) . '" name="' . htmlspecialchars($name, ENT_QUOTES) . '" value="' . htmlspecialchars($key, ENT_QUOTES) . '"' . $checked . $required . '>';
        $html .= '<span class="gender-choice__content">';
        $html .= '<span class="gender-choice__icon" aria-hidden="true">' . htmlspecialchars($meta['icon'], ENT_QUOTES) . '</span>';
        $html .= '<span class="gender-choice__label">' . htmlspecialchars($meta['label'], ENT_QUOTES) . '</span>';
        $html .= '</span>';
        $html .= '</label>';
    }

    $html .= '</div>';
    $html .= '</fieldset>';

    return $html;
}

function render_sex_badge(?string $value, array $options = []): string
{
    if ($value === null || $value === '') {
        return '';
    }

    $sex = normalize_sex($value);
    $genderOptions = get_gender_options();
    if (!isset($genderOptions[$sex])) {
        return '';
    }

    $meta = $genderOptions[$sex];
    $tag = $options['tag'] ?? 'span';
    $classes = trim('badge badge-gender ' . ($options['class'] ?? ''));
    $label = $meta['label'];
    $icon = $meta['icon'];

    return sprintf(
        '<%1$s class="%2$s" aria-label="%3$s" title="%3$s">%4$s %5$s</%1$s>',
        $tag,
        htmlspecialchars($classes, ENT_QUOTES),
        htmlspecialchars($label, ENT_QUOTES),
        htmlspecialchars($icon, ENT_QUOTES),
        htmlspecialchars($label, ENT_QUOTES)
    );
}

function build_page_meta(array $overrides = [], array $settings = []): array
{
    $path = $overrides['path'] ?? (parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/');
    $path = '/' . ltrim($path, '/');
    $title = trim($overrides['title'] ?? ($settings['site_title'] ?? SITE_NAME));
    $description = trim($overrides['description'] ?? ($settings['site_tagline'] ?? ($settings['hero_intro'] ?? PRIMARY_TOPIC)));
    $ogImage = $overrides['og_image'] ?? ($settings['hero_image'] ?? ORG_LOGO_URL);
    $type = $overrides['type'] ?? 'website';

    $fullTitle = $title === SITE_NAME ? $title : $title . ' | ' . SITE_NAME;

    return array_merge([
        'title' => $title,
        'full_title' => $fullTitle,
        'description' => $description,
        'path' => $path,
        'canonical' => canonical_url($path),
        'og_title' => $overrides['og_title'] ?? $title,
        'og_description' => $overrides['og_description'] ?? $description,
        'og_type' => $type,
        'og_image' => $ogImage,
        'lang' => PRIMARY_LANGUAGE,
        'breadcrumbs' => $overrides['breadcrumbs'] ?? [],
        'schema' => $overrides['schema'] ?? [],
    ], $overrides);
}

function build_breadcrumbs(array $items): array
{
    $position = 1;
    $trail = [];
    foreach ($items as $item) {
        $trail[] = [
            'name' => $item['name'],
            'url' => $item['url'] ?? canonical_url($item['path'] ?? ''),
            'position' => $position++,
        ];
    }
    return $trail;
}

function render_structured_data(array $blocks): string
{
    if (empty($blocks)) {
        return '';
    }

    $scripts = array_map(function ($block) {
        return '<script type="application/ld+json">' . json_encode($block, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>';
    }, $blocks);

    return implode("\n", $scripts);
}

function render_responsive_picture(?string $path, string $alt, array $options = []): string
{
    if (empty($path)) {
        return '';
    }

    $breakpoints = $options['breakpoints'] ?? [480, 768, 1024, 1600];
    $sizes = $options['sizes'] ?? '100vw';
    $class = $options['class'] ?? '';
    $loading = $options['loading'] ?? 'lazy';
    $decoding = $options['decoding'] ?? 'async';

    $normalized = ltrim($path, '/');
    $base = preg_replace('/\.[^.]+$/', '', $normalized);
    $directory = trim(dirname($base), './');
    $filename = basename($base);
    $prefix = 'media/generated' . ($directory ? '/' . $directory : '');

    $buildSrcset = static function (string $format) use ($prefix, $filename, $breakpoints): string {
        $parts = [];
        foreach ($breakpoints as $width) {
            $url = '/' . trim($prefix . '/' . $filename . '_' . $width . '.' . $format, '/');
            $parts[] = $url . ' ' . $width . 'w';
        }
        return implode(', ', $parts);
    };

    $avifSrcset = $buildSrcset('avif');
    $webpSrcset = $buildSrcset('webp');
    $fallback = '/' . $normalized;

    return sprintf(
        '<picture><source type="image/avif" srcset="%s" sizes="%s"><source type="image/webp" srcset="%s" sizes="%s"><img src="%s" alt="%s" loading="%s" decoding="%s" sizes="%s" class="%s"></picture>',
        htmlspecialchars($avifSrcset, ENT_QUOTES),
        htmlspecialchars($sizes, ENT_QUOTES),
        htmlspecialchars($webpSrcset, ENT_QUOTES),
        htmlspecialchars($sizes, ENT_QUOTES),
        htmlspecialchars($fallback, ENT_QUOTES),
        htmlspecialchars($alt, ENT_QUOTES),
        htmlspecialchars($loading, ENT_QUOTES),
        htmlspecialchars($decoding, ENT_QUOTES),
        htmlspecialchars($sizes, ENT_QUOTES),
        htmlspecialchars($class, ENT_QUOTES)
    );
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

function slugify(string $value): string
{
    $transliterated = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $value);
    if ($transliterated !== false) {
        $value = $transliterated;
    }
    $value = strtolower(trim($value));
    $value = preg_replace('/[^a-z0-9]+/i', '-', $value);
    $value = trim($value, '-');
    return $value ?: bin2hex(random_bytes(4));
}

function ensure_unique_slug(PDO $pdo, string $table, string $slug, ?int $ignoreId = null): string
{
    $base = $slug ?: bin2hex(random_bytes(4));
    $candidate = $base;
    $counter = 1;

    while (true) {
        $sql = "SELECT COUNT(*) FROM {$table} WHERE slug = :slug";
        $params = ['slug' => $candidate];
        if ($ignoreId !== null) {
            $sql .= ' AND id != :id';
            $params['id'] = $ignoreId;
        }
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        if ($stmt->fetchColumn() == 0) {
            return $candidate;
        }
        $candidate = $base . '-' . (++$counter);
    }
}

function render_rich_text(?string $value): string
{
    if ($value === null || $value === '') {
        return '';
    }

    if (strpos($value, '<') === false) {
        return nl2br(htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'));
    }

    return $value;
}
