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

$scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
$scriptDir = str_replace('\\', '/', dirname($scriptName));
if ($scriptDir === '/' || $scriptDir === '\\' || $scriptDir === '.' || $scriptDir === '') {
    $basePath = '';
} else {
    $basePath = rtrim($scriptDir, '/');
}
$GLOBALS['basePath'] = $basePath;

$baseDir = dirname(__DIR__);
$databaseFile = $baseDir . '/cms.db';
$uploadDir = $baseDir . '/static/uploads';

if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0775, true);
}

if (!is_writable($uploadDir)) {
    @chmod($uploadDir, 0775);
}

$requestPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/';

if ($basePath !== '' && $basePath !== '/') {
    if ($requestPath === $basePath || $requestPath === $basePath . '/') {
        $requestPath = '/';
    } elseif (str_starts_with($requestPath, $basePath . '/')) {
        $requestPath = substr($requestPath, strlen($basePath));
    }
}

$path = '/' . ltrim($requestPath, '/');
if ($path !== '/' && str_ends_with($path, '/')) {
    $path = rtrim($path, '/');
}
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

$requirements = checkRequirements($databaseFile, $uploadDir);
$pdo = null;
$databaseError = null;

if (requirementsExtensionsAvailable($requirements)) {
    try {
        $pdo = new PDO('sqlite:' . $databaseFile);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    } catch (PDOException $exception) {
        $databaseError = $exception->getMessage();
    }
}

if ($pdo) {
    initializeDatabase($pdo);
}

$isInstalled = $pdo ? cmsHasAdmin($pdo) : false;

if ($path === '/install') {
    if ($isInstalled) {
        flash('Das CMS wurde bereits eingerichtet. Bitte melde dich an.', 'info');
        redirect('/admin/login');
    }

    $canInstall = $pdo !== null && requirementsAreMet($requirements) && $databaseError === null;
    $formData = [
        'username' => trim($_POST['username'] ?? ''),
    ];

    if ($method === 'POST') {
        if (!$canInstall) {
            flash('Bitte erfülle zuerst alle Systemvoraussetzungen.', 'danger');
        } else {
            $username = trim($_POST['username'] ?? '');
            $password = $_POST['password'] ?? '';
            $confirmation = $_POST['password_confirmation'] ?? '';

            if ($username === '' || $password === '') {
                flash('Benutzername und Passwort dürfen nicht leer sein.', 'warning');
            } elseif (strlen($password) < 8) {
                flash('Das Passwort muss mindestens 8 Zeichen lang sein.', 'warning');
            } elseif ($password !== $confirmation) {
                flash('Die Passwortbestätigung stimmt nicht überein.', 'warning');
            } else {
                try {
                    createAdmin($pdo, $username, $password);
                    flash('Installation abgeschlossen. Du kannst dich jetzt anmelden.', 'success');
                    redirect('/admin/login');
                } catch (PDOException $exception) {
                    flash('Benutzername ist bereits vergeben.', 'danger');
                }
            }
        }
    }

    render('install', [
        'pageTitle' => 'Feroxz CMS installieren',
        'requirements' => $requirements,
        'canInstall' => $canInstall,
        'databaseError' => $databaseError,
        'formData' => $formData,
    ]);
    exit;
}

if (!$isInstalled) {
    redirect('/install');
}

if (!$pdo) {
    http_response_code(500);
    echo 'Die Datenbankverbindung konnte nicht hergestellt werden.';
    exit;
}

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

if ($path === '/genetics') {
    $species = fetchAll($pdo, 'SELECT id, name, slug, scientific_name, description FROM genetics_species ORDER BY name ASC');
    render('genetics/index', [
        'pageTitle' => 'Genetik-Datenbank - Feroxz CMS',
        'species' => $species,
    ]);
    exit;
}

if (preg_match('#^/genetics/([a-z0-9\-]+)/calculator$#i', $path, $matches)) {
    $speciesSlug = $matches[1];
    $species = fetchSpeciesBySlug($pdo, $speciesSlug);
    if (!$species) {
        render404();
        exit;
    }

    $genes = fetchGenesBySpecies($pdo, (int) $species['id']);

    $sireSelection = [];
    $damSelection = [];
    foreach ($genes as $gene) {
        $sireSelection[$gene['id']] = isset($_POST['sire'][$gene['id']]) ? max(0, min(2, (int) $_POST['sire'][$gene['id']])) : 0;
        $damSelection[$gene['id']] = isset($_POST['dam'][$gene['id']]) ? max(0, min(2, (int) $_POST['dam'][$gene['id']])) : 0;
    }

    $results = null;
    if ($method === 'POST' && $genes) {
        $geneResults = [];
        foreach ($genes as $gene) {
            $geneResults[$gene['id']] = calculateGeneOutcome($gene, $sireSelection[$gene['id']], $damSelection[$gene['id']]);
        }

        $combined = combineGeneOutcomes($genes, $geneResults);
        $results = [
            'genes' => $geneResults,
            'combinations' => $combined,
        ];
    }

    render('genetics/calculator', [
        'pageTitle' => 'Genetik-Rechner - ' . $species['name'],
        'species' => $species,
        'genes' => $genes,
        'sireSelection' => $sireSelection,
        'damSelection' => $damSelection,
        'results' => $results,
        'inheritanceLabels' => getInheritanceTypeLabels(),
    ]);
    exit;
}

if (preg_match('#^/genetics/([a-z0-9\-]+)$#i', $path, $matches)) {
    $speciesSlug = $matches[1];
    $species = fetchSpeciesBySlug($pdo, $speciesSlug);
    if (!$species) {
        render404();
        exit;
    }

    $genes = fetchGenesBySpecies($pdo, (int) $species['id']);
    render('genetics/species', [
        'pageTitle' => $species['name'] . ' Genetik - Feroxz CMS',
        'species' => $species,
        'genes' => $genes,
        'inheritanceLabels' => getInheritanceTypeLabels(),
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

if ($path === '/admin/genetics') {
    $species = fetchAll($pdo, 'SELECT gs.id, gs.name, gs.slug, gs.scientific_name, COUNT(gg.id) AS gene_count FROM genetics_species gs LEFT JOIN genetics_genes gg ON gg.species_id = gs.id GROUP BY gs.id, gs.name, gs.slug, gs.scientific_name ORDER BY gs.name ASC');
    render('admin/genetics/index', [
        'pageTitle' => 'Genetikverwaltung - Feroxz CMS',
        'species' => $species,
    ]);
    exit;
}

if (preg_match('#^/admin/genetics/species/([a-z0-9\-]+)$#i', $path, $matches) && $method === 'GET') {
    $speciesSlug = $matches[1];
    $species = fetchSpeciesBySlug($pdo, $speciesSlug);
    if (!$species) {
        flash('Art wurde nicht gefunden.', 'danger');
        redirect('/admin/genetics');
    }

    $genes = fetchGenesBySpecies($pdo, (int) $species['id']);
    render('admin/genetics/species', [
        'pageTitle' => 'Genetik für ' . $species['name'],
        'species' => $species,
        'genes' => $genes,
        'inheritanceLabels' => getInheritanceTypeLabels(),
    ]);
    exit;
}

if (preg_match('#^/admin/genetics/species/([a-z0-9\-]+)/genes/new$#i', $path, $matches)) {
    $speciesSlug = $matches[1];
    $species = fetchSpeciesBySlug($pdo, $speciesSlug);
    if (!$species) {
        flash('Art wurde nicht gefunden.', 'danger');
        redirect('/admin/genetics');
    }

    $inheritanceTypes = array_keys(getInheritanceTypeLabels());
    if ($method === 'POST') {
        $name = trim($_POST['name'] ?? '');
        $slug = sanitizeSlug($_POST['slug'] ?? $name);
        $inheritance = $_POST['inheritance_type'] ?? '';
        $normal = trim($_POST['normal_label'] ?? '');
        $hetero = trim($_POST['heterozygous_label'] ?? '');
        $homo = trim($_POST['homozygous_label'] ?? '');
        $description = trim($_POST['description'] ?? '');

        if ($name === '' || $slug === '' || $normal === '' || $hetero === '' || $homo === '' || !in_array($inheritance, $inheritanceTypes, true)) {
            flash('Bitte fülle alle Pflichtfelder aus und wähle einen gültigen Vererbungsmodus.', 'warning');
        } else {
            try {
                $stmt = $pdo->prepare('INSERT INTO genetics_genes (species_id, name, slug, inheritance_type, normal_label, heterozygous_label, homozygous_label, description, created_at, updated_at) VALUES (:species_id, :name, :slug, :inheritance_type, :normal_label, :heterozygous_label, :homozygous_label, :description, :created_at, :updated_at)');
                $now = currentTimestamp();
                $stmt->execute([
                    'species_id' => $species['id'],
                    'name' => $name,
                    'slug' => $slug,
                    'inheritance_type' => $inheritance,
                    'normal_label' => $normal,
                    'heterozygous_label' => $hetero,
                    'homozygous_label' => $homo,
                    'description' => $description,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
                flash('Gen wurde angelegt.', 'success');
                redirect('/admin/genetics/species/' . $species['slug']);
            } catch (PDOException $e) {
                flash('Slug ist bereits vergeben.', 'danger');
            }
        }
    }

    render('admin/genetics/gene_form', [
        'pageTitle' => 'Neues Gen für ' . $species['name'],
        'species' => $species,
        'gene' => null,
        'inheritanceLabels' => getInheritanceTypeLabels(),
    ]);
    exit;
}

if (preg_match('#^/admin/genetics/species/([a-z0-9\-]+)/genes/(\d+)/edit$#i', $path, $matches)) {
    $speciesSlug = $matches[1];
    $geneId = (int) $matches[2];
    $species = fetchSpeciesBySlug($pdo, $speciesSlug);
    if (!$species) {
        flash('Art wurde nicht gefunden.', 'danger');
        redirect('/admin/genetics');
    }

    $gene = fetchOne($pdo, 'SELECT * FROM genetics_genes WHERE id = :id AND species_id = :species_id', ['id' => $geneId, 'species_id' => $species['id']]);
    if (!$gene) {
        flash('Gen wurde nicht gefunden.', 'danger');
        redirect('/admin/genetics/species/' . $species['slug']);
    }

    $inheritanceTypes = array_keys(getInheritanceTypeLabels());
    if ($method === 'POST') {
        $name = trim($_POST['name'] ?? '');
        $slug = sanitizeSlug($_POST['slug'] ?? $gene['slug']);
        $inheritance = $_POST['inheritance_type'] ?? '';
        $normal = trim($_POST['normal_label'] ?? '');
        $hetero = trim($_POST['heterozygous_label'] ?? '');
        $homo = trim($_POST['homozygous_label'] ?? '');
        $description = trim($_POST['description'] ?? '');

        if ($name === '' || $slug === '' || $normal === '' || $hetero === '' || $homo === '' || !in_array($inheritance, $inheritanceTypes, true)) {
            flash('Bitte fülle alle Pflichtfelder aus und wähle einen gültigen Vererbungsmodus.', 'warning');
        } else {
            try {
                $stmt = $pdo->prepare('UPDATE genetics_genes SET name = :name, slug = :slug, inheritance_type = :inheritance_type, normal_label = :normal_label, heterozygous_label = :heterozygous_label, homozygous_label = :homozygous_label, description = :description, updated_at = :updated_at WHERE id = :id');
                $stmt->execute([
                    'name' => $name,
                    'slug' => $slug,
                    'inheritance_type' => $inheritance,
                    'normal_label' => $normal,
                    'heterozygous_label' => $hetero,
                    'homozygous_label' => $homo,
                    'description' => $description,
                    'updated_at' => currentTimestamp(),
                    'id' => $geneId,
                ]);
                flash('Gen wurde aktualisiert.', 'success');
                redirect('/admin/genetics/species/' . $species['slug']);
            } catch (PDOException $e) {
                flash('Slug ist bereits vergeben.', 'danger');
            }
        }
    }

    render('admin/genetics/gene_form', [
        'pageTitle' => 'Gen bearbeiten - ' . $gene['name'],
        'species' => $species,
        'gene' => $gene,
        'inheritanceLabels' => getInheritanceTypeLabels(),
    ]);
    exit;
}

if (preg_match('#^/admin/genetics/species/([a-z0-9\-]+)/genes/(\d+)/delete$#i', $path, $matches) && $method === 'POST') {
    $speciesSlug = $matches[1];
    $geneId = (int) $matches[2];
    $species = fetchSpeciesBySlug($pdo, $speciesSlug);
    if (!$species) {
        flash('Art wurde nicht gefunden.', 'danger');
        redirect('/admin/genetics');
    }

    $pdo->prepare('DELETE FROM genetics_genes WHERE id = :id AND species_id = :species_id')->execute([
        'id' => $geneId,
        'species_id' => $species['id'],
    ]);
    flash('Gen wurde gelöscht.', 'info');
    redirect('/admin/genetics/species/' . $species['slug']);
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
    if ($location !== '' && $location[0] === '/' && !str_starts_with($location, '//')) {
        $basePath = basePath();
        if ($basePath !== '') {
            $location = $basePath . $location;
        }
    }

    header('Location: ' . $location);
    exit;
}

function basePath(): string
{
    $basePath = $GLOBALS['basePath'] ?? '';
    if ($basePath === '/' || $basePath === '\\' || $basePath === '.' || $basePath === '') {
        return '';
    }

    return $basePath;
}

function path(string $path = '/'): string
{
    $normalized = '/' . ltrim($path, '/');
    if ($normalized === '//') {
        $normalized = '/';
    }

    $basePath = basePath();
    if ($normalized === '/') {
        return $basePath === '' ? '/' : $basePath;
    }

    return ($basePath === '' ? '' : $basePath) . $normalized;
}

function asset(string $assetPath): string
{
    return path('/' . ltrim($assetPath, '/'));
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

function cmsHasAdmin(PDO $pdo): bool
{
    $stmt = $pdo->query('SELECT COUNT(*) FROM admins');
    return (int) $stmt->fetchColumn() > 0;
}

function createAdmin(PDO $pdo, string $username, string $password): void
{
    $stmt = $pdo->prepare('INSERT INTO admins (username, password_hash) VALUES (:username, :password_hash)');
    $stmt->execute([
        'username' => $username,
        'password_hash' => password_hash($password, PASSWORD_DEFAULT),
    ]);
}

function checkRequirements(string $databaseFile, string $uploadDir): array
{
    $extensions = [
        'pdo' => [
            'label' => 'PDO',
            'required' => true,
            'met' => extension_loaded('pdo'),
        ],
        'pdo_sqlite' => [
            'label' => 'PDO_SQLite',
            'required' => true,
            'met' => extension_loaded('pdo_sqlite'),
        ],
        'sqlite3' => [
            'label' => 'SQLite3',
            'required' => true,
            'met' => extension_loaded('sqlite3'),
        ],
        'fileinfo' => [
            'label' => 'Fileinfo (für Uploads empfohlen)',
            'required' => false,
            'met' => extension_loaded('fileinfo'),
        ],
    ];

    $databaseExists = file_exists($databaseFile);
    $databaseTarget = $databaseExists ? $databaseFile : dirname($databaseFile);

    $paths = [
        'database' => [
            'label' => $databaseExists ? 'Datenbankdatei beschreibbar' : 'Datenbankverzeichnis beschreibbar',
            'path' => $databaseTarget,
            'required' => true,
            'met' => is_writable($databaseTarget),
            'exists' => $databaseExists,
        ],
        'uploads' => [
            'label' => 'Upload-Verzeichnis beschreibbar',
            'path' => $uploadDir,
            'required' => true,
            'met' => is_dir($uploadDir) && is_writable($uploadDir),
            'exists' => is_dir($uploadDir),
        ],
    ];

    return [
        'extensions' => $extensions,
        'paths' => $paths,
    ];
}

function requirementsExtensionsAvailable(array $requirements): bool
{
    if (!isset($requirements['extensions']) || !is_array($requirements['extensions'])) {
        return false;
    }

    foreach ($requirements['extensions'] as $extension) {
        if (!empty($extension['required']) && empty($extension['met'])) {
            return false;
        }
    }

    return true;
}

function requirementsAreMet(array $requirements): bool
{
    if (!requirementsExtensionsAvailable($requirements)) {
        return false;
    }

    if (empty($requirements['paths']) || !is_array($requirements['paths'])) {
        return false;
    }

    foreach ($requirements['paths'] as $path) {
        if (!empty($path['required']) && empty($path['met'])) {
            return false;
        }
    }

    return true;
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

function fetchSpeciesBySlug(PDO $pdo, string $slug): ?array
{
    return fetchOne($pdo, 'SELECT id, name, slug, scientific_name, description FROM genetics_species WHERE slug = :slug', ['slug' => $slug]);
}

function fetchGenesBySpecies(PDO $pdo, int $speciesId): array
{
    return fetchAll($pdo, 'SELECT id, species_id, name, slug, inheritance_type, normal_label, heterozygous_label, homozygous_label, description, created_at, updated_at FROM genetics_genes WHERE species_id = :species_id ORDER BY name ASC', ['species_id' => $speciesId]);
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

function getInheritanceTypeLabels(): array
{
    return [
        'recessive' => 'Rezessiv',
        'dominant' => 'Dominant',
        'incomplete_dominant' => 'Unvollständig dominant',
    ];
}

function calculateGeneOutcome(array $gene, int $sire, int $dam): array
{
    $distribution = calculateGeneDistribution($sire, $dam);
    $states = [];
    foreach ($distribution as $count => $probability) {
        if ($probability <= 0) {
            continue;
        }
        $states[] = [
            'count' => $count,
            'probability' => $probability,
            'label' => geneStateLabel($gene, $count),
        ];
    }

    return [
        'gene' => $gene,
        'sire' => $sire,
        'dam' => $dam,
        'distribution' => $states,
    ];
}

function combineGeneOutcomes(array $genes, array $geneResults): array
{
    if (!$geneResults) {
        return [];
    }

    $combinations = [[
        'labels' => [],
        'counts' => [],
        'probability' => 1.0,
    ]];

    foreach ($genes as $gene) {
        $result = $geneResults[$gene['id']] ?? null;
        if (!$result) {
            continue;
        }

        $next = [];
        foreach ($combinations as $combo) {
            foreach ($result['distribution'] as $state) {
                $next[] = [
                    'labels' => array_merge($combo['labels'], [$gene['name'] => $state['label']]),
                    'counts' => array_merge($combo['counts'], [$gene['name'] => $state['count']]),
                    'probability' => $combo['probability'] * $state['probability'],
                ];
            }
        }
        $combinations = $next;
    }

    $aggregated = [];
    foreach ($combinations as $combo) {
        $key = json_encode($combo['counts']);
        if (!isset($aggregated[$key])) {
            $aggregated[$key] = $combo;
        } else {
            $aggregated[$key]['probability'] += $combo['probability'];
        }
    }

    $result = array_values($aggregated);
    usort($result, static fn(array $a, array $b) => $b['probability'] <=> $a['probability']);

    return $result;
}

function calculateGeneDistribution(int $sire, int $dam): array
{
    $gametesA = createGametes($sire);
    $gametesB = createGametes($dam);
    $distribution = [0 => 0.0, 1 => 0.0, 2 => 0.0];

    foreach ($gametesA as $alleleA => $probA) {
        foreach ($gametesB as $alleleB => $probB) {
            $distribution[$alleleA + $alleleB] += $probA * $probB;
        }
    }

    return $distribution;
}

function createGametes(int $genotype): array
{
    switch ($genotype) {
        case 2:
            return [1 => 1.0];
        case 1:
            return [1 => 0.5, 0 => 0.5];
        default:
            return [0 => 1.0];
    }
}

function geneStateLabel(array $gene, int $count): string
{
    if ($count <= 0) {
        return $gene['normal_label'];
    }

    if ($count === 1) {
        return $gene['heterozygous_label'];
    }

    return $gene['homozygous_label'];
}

function formatProbability(float $probability): string
{
    return number_format($probability * 100, 2, ',', '.') . ' %';
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

    $pdo->exec('CREATE TABLE IF NOT EXISTS genetics_species (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL,
        scientific_name TEXT,
        slug TEXT NOT NULL UNIQUE,
        description TEXT
    )');

    $pdo->exec('CREATE TABLE IF NOT EXISTS genetics_genes (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        species_id INTEGER NOT NULL,
        name TEXT NOT NULL,
        slug TEXT NOT NULL,
        inheritance_type TEXT NOT NULL,
        normal_label TEXT NOT NULL,
        heterozygous_label TEXT NOT NULL,
        homozygous_label TEXT NOT NULL,
        description TEXT,
        created_at TEXT NOT NULL,
        updated_at TEXT NOT NULL,
        UNIQUE(species_id, slug),
        FOREIGN KEY (species_id) REFERENCES genetics_species(id) ON DELETE CASCADE
    )');

    $pdo->exec('CREATE TABLE IF NOT EXISTS admins (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        username TEXT NOT NULL UNIQUE,
        password_hash TEXT NOT NULL
    )');

    $speciesSeeds = [
        [
            'slug' => 'pogona-vitticeps',
            'name' => 'Bartagame',
            'scientific_name' => 'Pogona vitticeps',
            'description' => 'Stammt aus Australien und ist für eine Vielzahl gut dokumentierter Morphe bekannt.',
        ],
        [
            'slug' => 'heterodon-nasicus',
            'name' => 'Westliche Hakennasennatter',
            'scientific_name' => 'Heterodon nasicus',
            'description' => 'Beliebt im Terraristik-Hobby mit zahlreichen rezessiven und codominanten Farbformen.',
        ],
    ];

    $speciesSelect = $pdo->prepare('SELECT id FROM genetics_species WHERE slug = :slug');
    $speciesInsert = $pdo->prepare('INSERT INTO genetics_species (name, scientific_name, slug, description) VALUES (:name, :scientific_name, :slug, :description)');
    foreach ($speciesSeeds as $seed) {
        $speciesSelect->execute(['slug' => $seed['slug']]);
        if (!$speciesSelect->fetch()) {
            $speciesInsert->execute([
                'name' => $seed['name'],
                'scientific_name' => $seed['scientific_name'],
                'slug' => $seed['slug'],
                'description' => $seed['description'],
            ]);
        }
    }

    $defaultGenes = [
        'pogona-vitticeps' => [
            [
                'slug' => 'albino',
                'name' => 'Albino',
                'inheritance_type' => 'recessive',
                'normal_label' => 'Normal',
                'heterozygous_label' => 'Het Albino',
                'homozygous_label' => 'Albino',
                'description' => 'Rezessiver Farbschlag ohne schwarze Pigmente.',
            ],
            [
                'slug' => 'hypo',
                'name' => 'Hypomelanistic',
                'inheritance_type' => 'recessive',
                'normal_label' => 'Normal',
                'heterozygous_label' => 'Het Hypo',
                'homozygous_label' => 'Hypo',
                'description' => 'Rezessiv, sorgt für stark reduzierte schwarze Zeichnung.',
            ],
            [
                'slug' => 'witblits',
                'name' => 'Witblits',
                'inheritance_type' => 'recessive',
                'normal_label' => 'Normal',
                'heterozygous_label' => 'Het Witblits',
                'homozygous_label' => 'Witblits',
                'description' => 'Rezessiver, sehr heller Farbschlag mit nahezu einfarbiger Grundfarbe.',
            ],
            [
                'slug' => 'leatherback',
                'name' => 'Leatherback',
                'inheritance_type' => 'incomplete_dominant',
                'normal_label' => 'Normal',
                'heterozygous_label' => 'Leatherback',
                'homozygous_label' => 'Super Leatherback',
                'description' => 'Unvollständig dominanter Schuppenfaktor mit reduzierten Tuberkeln.',
            ],
        ],
        'heterodon-nasicus' => [
            [
                'slug' => 'albino',
                'name' => 'Albino',
                'inheritance_type' => 'recessive',
                'normal_label' => 'Normal',
                'heterozygous_label' => 'Het Albino',
                'homozygous_label' => 'Albino',
                'description' => 'Rezessiver Farbschlag mit roten Augen und fehlenden dunklen Pigmenten.',
            ],
            [
                'slug' => 'anaconda',
                'name' => 'Anaconda',
                'inheritance_type' => 'incomplete_dominant',
                'normal_label' => 'Normal',
                'heterozygous_label' => 'Anaconda',
                'homozygous_label' => 'Super Anaconda',
                'description' => 'Unvollständig dominanter Pattern-Reducer.',
            ],
            [
                'slug' => 'lavender',
                'name' => 'Lavender',
                'inheritance_type' => 'recessive',
                'normal_label' => 'Normal',
                'heterozygous_label' => 'Het Lavender',
                'homozygous_label' => 'Lavender',
                'description' => 'Rezessiv, erzeugt pastellige violette Farbtöne.',
            ],
            [
                'slug' => 'conda-sable',
                'name' => 'Sable',
                'inheritance_type' => 'recessive',
                'normal_label' => 'Normal',
                'heterozygous_label' => 'Het Sable',
                'homozygous_label' => 'Sable',
                'description' => 'Dunkler rezessiver Farbschlag, der häufig mit Anaconda kombiniert wird.',
            ],
        ],
    ];

    $speciesIdStmt = $pdo->prepare('SELECT id FROM genetics_species WHERE slug = :slug');
    $geneExistsStmt = $pdo->prepare('SELECT id FROM genetics_genes WHERE species_id = :species_id AND slug = :slug');
    $geneInsertStmt = $pdo->prepare('INSERT INTO genetics_genes (species_id, name, slug, inheritance_type, normal_label, heterozygous_label, homozygous_label, description, created_at, updated_at) VALUES (:species_id, :name, :slug, :inheritance_type, :normal_label, :heterozygous_label, :homozygous_label, :description, :created_at, :updated_at)');

    foreach ($defaultGenes as $speciesSlug => $genes) {
        $speciesIdStmt->execute(['slug' => $speciesSlug]);
        $speciesRow = $speciesIdStmt->fetch();
        if (!$speciesRow || empty($speciesRow['id'])) {
            continue;
        }

        foreach ($genes as $gene) {
            $geneExistsStmt->execute([
                'species_id' => $speciesRow['id'],
                'slug' => $gene['slug'],
            ]);
            if ($geneExistsStmt->fetch()) {
                continue;
            }

            $timestamp = currentTimestamp();
            $geneInsertStmt->execute([
                'species_id' => $speciesRow['id'],
                'name' => $gene['name'],
                'slug' => $gene['slug'],
                'inheritance_type' => $gene['inheritance_type'],
                'normal_label' => $gene['normal_label'],
                'heterozygous_label' => $gene['heterozygous_label'],
                'homozygous_label' => $gene['homozygous_label'],
                'description' => $gene['description'],
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ]);
        }
    }
}
