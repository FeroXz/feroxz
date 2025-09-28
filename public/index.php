<?php
session_start();

define('BASE_PATH', dirname(__DIR__));
define('STORAGE_PATH', BASE_PATH . '/storage');
define('DB_PATH', STORAGE_PATH . '/cms.sqlite');
define('UPLOAD_PATH', __DIR__ . '/uploads');

date_default_timezone_set('Europe/Berlin');

if (!is_dir(STORAGE_PATH)) {
    mkdir(STORAGE_PATH, 0775, true);
}

if (!is_dir(UPLOAD_PATH)) {
    mkdir(UPLOAD_PATH, 0775, true);
}

$pdo = new PDO('sqlite:' . DB_PATH);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
$pdo->exec('PRAGMA foreign_keys = ON');

initializeDatabase($pdo);

$route = $_GET['route'] ?? 'home';
$method = $_SERVER['REQUEST_METHOD'];

switch ($route) {
    case 'home':
        render('home', [
            'posts' => fetchPosts($pdo, 6),
            'pages' => fetchPages($pdo),
            'gallery' => fetchGalleryItems($pdo, 6)
        ]);
        break;
    case 'post':
        $slug = $_GET['slug'] ?? '';
        $post = findPostBySlug($pdo, $slug);
        if (!$post) {
            notFound();
        }
        render('post', ['post' => $post, 'pages' => fetchPages($pdo)]);
        break;
    case 'page':
        $slug = $_GET['slug'] ?? '';
        $page = findPageBySlug($pdo, $slug);
        if (!$page) {
            notFound();
        }
        render('page', ['page' => $page, 'pages' => fetchPages($pdo)]);
        break;
    case 'gallery':
        render('gallery', [
            'items' => fetchGalleryItems($pdo),
            'pages' => fetchPages($pdo)
        ]);
        break;
    case 'genetics':
        render('genetics/index', [
            'species' => fetchSpecies($pdo),
            'pages' => fetchPages($pdo)
        ]);
        break;
    case 'genetics/species':
        $slug = $_GET['slug'] ?? '';
        $species = findSpeciesBySlug($pdo, $slug);
        if (!$species) {
            notFound();
        }
        $genes = fetchGenesForSpecies($pdo, $species['id']);
        render('genetics/species', [
            'species' => $species,
            'genes' => $genes,
            'pages' => fetchPages($pdo)
        ]);
        break;
    case 'genetics/calculator':
        handleGeneticsCalculator($pdo, $method);
        break;
    case 'login':
        handleLogin($pdo, $method);
        break;
    case 'logout':
        session_destroy();
        header('Location: ' . url('login'));
        exit;
    case 'admin':
        requireAdmin();
        renderAdmin('dashboard', [
            'postCount' => countAll($pdo, 'posts'),
            'pageCount' => countAll($pdo, 'pages'),
            'galleryCount' => countAll($pdo, 'gallery_items'),
            'speciesCount' => countAll($pdo, 'species')
        ]);
        break;
    case 'admin/posts':
        requireAdmin();
        handlePosts($pdo, $method);
        break;
    case 'admin/pages':
        requireAdmin();
        handlePages($pdo, $method);
        break;
    case 'admin/gallery':
        requireAdmin();
        handleGallery($pdo, $method);
        break;
    case 'admin/genetics':
        requireAdmin();
        handleGeneticsAdmin($pdo, $method);
        break;
    default:
        notFound();
}

function initializeDatabase(PDO $pdo): void
{
    $pdo->exec('CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        username TEXT UNIQUE NOT NULL,
        password_hash TEXT NOT NULL,
        created_at TEXT NOT NULL
    )');

    $pdo->exec('CREATE TABLE IF NOT EXISTS posts (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        title TEXT NOT NULL,
        slug TEXT UNIQUE NOT NULL,
        excerpt TEXT,
        content TEXT NOT NULL,
        published_at TEXT NOT NULL,
        updated_at TEXT NOT NULL
    )');

    $pdo->exec('CREATE TABLE IF NOT EXISTS pages (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        title TEXT NOT NULL,
        slug TEXT UNIQUE NOT NULL,
        content TEXT NOT NULL,
        updated_at TEXT NOT NULL
    )');

    $pdo->exec('CREATE TABLE IF NOT EXISTS gallery_items (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        title TEXT NOT NULL,
        description TEXT,
        image_path TEXT,
        created_at TEXT NOT NULL
    )');

    $pdo->exec('CREATE TABLE IF NOT EXISTS species (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        slug TEXT UNIQUE NOT NULL,
        latin_name TEXT NOT NULL,
        common_name TEXT NOT NULL,
        description TEXT NOT NULL,
        habitat TEXT,
        care_notes TEXT
    )');

    $pdo->exec('CREATE TABLE IF NOT EXISTS genes (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        species_id INTEGER NOT NULL,
        name TEXT NOT NULL,
        inheritance TEXT NOT NULL,
        description TEXT,
        visuals TEXT,
        FOREIGN KEY (species_id) REFERENCES species(id) ON DELETE CASCADE
    )');

    if ((int)$pdo->query('SELECT COUNT(*) FROM users')->fetchColumn() === 0) {
        $stmt = $pdo->prepare('INSERT INTO users (username, password_hash, created_at) VALUES (?, ?, ?)');
        $stmt->execute([
            'admin',
            password_hash('12345678', PASSWORD_DEFAULT),
            date(DATE_ATOM)
        ]);
    }

    if ((int)$pdo->query('SELECT COUNT(*) FROM posts')->fetchColumn() === 0) {
        seedContent($pdo);
    }
}

function seedContent(PDO $pdo): void
{
    $now = date(DATE_ATOM);

    $posts = [
        [
            'Willkommen im Feroxz CMS',
            'willkommen-feroxz-cms',
            'Starte mit dem integrierten Adminbereich, um Inhalte zu verwalten.',
            'Dieses PHP-basierte CMS speichert deine Inhalte persistent in einer SQLite-Datenbank. Melde dich im Adminbereich an, um Beiträge, Seiten, Galerie und Genetikdaten zu pflegen.',
            $now,
            $now
        ],
        [
            'Neue Bartagamen eingetroffen',
            'neue-bartagamen',
            'Unsere Ausstellung wurde um weitere Farbmorphen ergänzt.',
            'Entdecke eine breite Auswahl an *Pogona vitticeps* Morphen – von Hypomelanistic bis Translucent. Alle Tiere werden inklusive Herkunfts- und Genetikangaben gepflegt.',
            $now,
            $now
        ]
    ];
    $stmt = $pdo->prepare('INSERT INTO posts (title, slug, excerpt, content, published_at, updated_at) VALUES (?, ?, ?, ?, ?, ?)');
    foreach ($posts as $post) {
        $stmt->execute($post);
    }

    $pages = [
        ['Über uns', 'about', 'Feroxz konzentriert sich auf die Zucht gesunder Reptilienlinien. Wir teilen unser Wissen über Workshops, Artikel und persönliche Beratung.', $now],
        ['Pflegeleitfaden', 'pflegeleitfaden', 'Ein artgerechtes Terrarium beginnt mit ausreichender Beleuchtung, Temperaturzonen und hochwertiger Ernährung. Unsere Tipps basieren auf jahrelanger Erfahrung.', $now]
    ];
    $stmt = $pdo->prepare('INSERT INTO pages (title, slug, content, updated_at) VALUES (?, ?, ?, ?)');
    foreach ($pages as $page) {
        $stmt->execute($page);
    }

    $gallery = [
        ['Red Sandfire', 'Satte Rotfärbung kombiniert mit klarem Rückenmuster.', 'https://images.unsplash.com/photo-1581888227599-779811939961?auto=format&fit=crop&w=900&q=80', $now],
        ['Snow Het Albino', 'Jungtier mit sichtbarer Hypo-Zeichnung und Albino-Trägerstatus.', 'https://images.unsplash.com/photo-1618826411640-7af7403d7561?auto=format&fit=crop&w=900&q=80', $now],
        ['Heterodon nasicus – Anaconda', 'Co-dominante Linie mit reduzierter Fleckung entlang des Rückens.', 'https://images.unsplash.com/photo-1529470859839-9016f6ab3c1c?auto=format&fit=crop&w=900&q=80', $now]
    ];
    $stmt = $pdo->prepare('INSERT INTO gallery_items (title, description, image_path, created_at) VALUES (?, ?, ?, ?)');
    foreach ($gallery as $item) {
        $stmt->execute($item);
    }

    $speciesStmt = $pdo->prepare('INSERT INTO species (slug, latin_name, common_name, description, habitat, care_notes) VALUES (?, ?, ?, ?, ?, ?)');
    $speciesStmt->execute([
        'pogona-vitticeps',
        'Pogona vitticeps',
        'Bartagame',
        'Die Bartagame zählt zu den beliebtesten Terrarientieren. Dank zahlreicher Morphen lässt sich eine Vielzahl an Farbschlägen züchten.',
        'Trockene Busch- und Steppengebiete im Osten Australiens.',
        'Temperaturtagsbereich 28–32 °C mit lokalem Hotspot bis 42 °C. UVB-Versorgung und abwechslungsreiche Ernährung sind essenziell.'
    ]);
    $pogonaId = (int)$pdo->lastInsertId();

    $speciesStmt->execute([
        'heterodon-nasicus',
        'Heterodon nasicus',
        'Westliche Hakennatter',
        'Bekannt für ihre upturned Schnauze und ein breites Spektrum an Farbmutationen.',
        'Trockene Prärien Nordamerikas mit sandigen Böden für Grabaktivität.',
        'Tagsüber 26–29 °C, Sonnenplatz bis 34 °C, Nachtabsenkung auf 22 °C. Substrat zum Eingraben sowie abwechslungsreiche Nagetierkost.'
    ]);
    $heterodonId = (int)$pdo->lastInsertId();

    $geneStmt = $pdo->prepare('INSERT INTO genes (species_id, name, inheritance, description, visuals) VALUES (?, ?, ?, ?, ?)');
    $geneStmt->execute([
        $pogonaId,
        'Albino',
        'recessive',
        'Rezessive Mutation ohne Melanin. Tiere zeigen eine helle Grundfärbung mit roten Augen.',
        json_encode([
            'dominant' => 'Normal',
            'heterozygous' => 'Het Albino',
            'recessive' => 'Albino'
        ], JSON_UNESCAPED_UNICODE)
    ]);
    $geneStmt->execute([
        $pogonaId,
        'Hypomelanistic',
        'recessive',
        'Reduzierter Melaninanteil sorgt für pastellfarbene Tiere mit klaren Nägeln.',
        json_encode([
            'dominant' => 'Normal',
            'heterozygous' => 'Het Hypo',
            'recessive' => 'Hypo'
        ], JSON_UNESCAPED_UNICODE)
    ]);
    $geneStmt->execute([
        $pogonaId,
        'Translucent',
        'co-dominant',
        'Teiltransparentes Schuppenkleid, dunkle Augen und verstärkte Blautöne im Bauchbereich.',
        json_encode([
            'dominant' => 'Super Translucent',
            'heterozygous' => 'Translucent',
            'recessive' => 'Normal'
        ], JSON_UNESCAPED_UNICODE)
    ]);

    $geneStmt->execute([
        $heterodonId,
        'Albino',
        'recessive',
        'Rezessive Mutation mit pink-gelber Zeichnung und roten Augen.',
        json_encode([
            'dominant' => 'Normal',
            'heterozygous' => 'Het Albino',
            'recessive' => 'Albino'
        ], JSON_UNESCAPED_UNICODE)
    ]);
    $geneStmt->execute([
        $heterodonId,
        'Anaconda',
        'co-dominant',
        'Co-dominante Mutation mit reduzierter Fleckung. Super-Form („Superconda“) ist nahezu zeichnungslos.',
        json_encode([
            'dominant' => 'Superconda',
            'heterozygous' => 'Anaconda',
            'recessive' => 'Normal'
        ], JSON_UNESCAPED_UNICODE)
    ]);
    $geneStmt->execute([
        $heterodonId,
        'Toffee Belly',
        'recessive',
        'Rezessive Mutation, die für karamellfarbene Unterseite und wärmere Grundfarben sorgt.',
        json_encode([
            'dominant' => 'Normal',
            'heterozygous' => 'Het Toffee Belly',
            'recessive' => 'Toffee Belly'
        ], JSON_UNESCAPED_UNICODE)
    ]);
}

function render(string $view, array $params = [], string $layout = 'layout'): void
{
    $viewFile = __DIR__ . '/views/' . $view . '.php';
    if (!file_exists($viewFile)) {
        notFound();
    }

    extract($params);
    $pages ??= [];
    ob_start();
    include $viewFile;
    $content = ob_get_clean();

    include __DIR__ . '/views/' . $layout . '.php';
}

function renderAdmin(string $view, array $params = []): void
{
    render('admin/' . $view, $params, 'admin/layout');
}

function baseUrl(): string
{
    $scriptName = dirname($_SERVER['SCRIPT_NAME']);
    $scriptName = $scriptName === '/' ? '' : $scriptName;
    return $scriptName;
}

function url(string $route, array $params = []): string
{
    $query = http_build_query(array_merge(['route' => $route], $params));
    return baseUrl() . '/index.php?' . $query;
}

function asset(string $path): string
{
    return baseUrl() . '/' . ltrim($path, '/');
}

function fetchPosts(PDO $pdo, int $limit = 20): array
{
    $stmt = $pdo->prepare('SELECT * FROM posts ORDER BY published_at DESC LIMIT ?');
    $stmt->bindValue(1, $limit, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll();
}

function findPostBySlug(PDO $pdo, string $slug): ?array
{
    $stmt = $pdo->prepare('SELECT * FROM posts WHERE slug = ?');
    $stmt->execute([$slug]);
    $post = $stmt->fetch();
    return $post ?: null;
}

function fetchPages(PDO $pdo): array
{
    $stmt = $pdo->query('SELECT * FROM pages ORDER BY title ASC');
    return $stmt->fetchAll();
}

function findPageBySlug(PDO $pdo, string $slug): ?array
{
    $stmt = $pdo->prepare('SELECT * FROM pages WHERE slug = ?');
    $stmt->execute([$slug]);
    $page = $stmt->fetch();
    return $page ?: null;
}

function fetchGalleryItems(PDO $pdo, int $limit = 0): array
{
    $sql = 'SELECT * FROM gallery_items ORDER BY datetime(created_at) DESC';
    if ($limit > 0) {
        $sql .= ' LIMIT ' . (int)$limit;
    }
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll();
}

function fetchSpecies(PDO $pdo): array
{
    $stmt = $pdo->query('SELECT * FROM species ORDER BY common_name ASC');
    return $stmt->fetchAll();
}

function findSpeciesBySlug(PDO $pdo, string $slug): ?array
{
    $stmt = $pdo->prepare('SELECT * FROM species WHERE slug = ?');
    $stmt->execute([$slug]);
    $species = $stmt->fetch();
    return $species ?: null;
}

function fetchGenesForSpecies(PDO $pdo, int $speciesId): array
{
    $stmt = $pdo->prepare('SELECT * FROM genes WHERE species_id = ? ORDER BY name ASC');
    $stmt->execute([$speciesId]);
    $genes = $stmt->fetchAll();
    foreach ($genes as &$gene) {
        $gene['visuals'] = $gene['visuals'] ? json_decode($gene['visuals'], true) : [];
    }
    return $genes;
}

function countAll(PDO $pdo, string $table): int
{
    return (int)$pdo->query('SELECT COUNT(*) FROM ' . $table)->fetchColumn();
}

function requireAdmin(): void
{
    if (!isset($_SESSION['user_id'])) {
        header('Location: ' . url('login'));
        exit;
    }
}

function handleLogin(PDO $pdo, string $method): void
{
    if ($method === 'POST') {
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        $stmt = $pdo->prepare('SELECT * FROM users WHERE username = ?');
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password_hash'])) {
            session_regenerate_id(true);
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            setFlash('Willkommen zurück, ' . htmlspecialchars($user['username']) . '!', 'success');
            header('Location: ' . url('admin'));
            exit;
        }

        setFlash('Ungültige Zugangsdaten.', 'error');
    }

    render('login', ['pages' => fetchPages($pdo)]);
}

function setFlash(string $message, string $type = 'info'): void
{
    $_SESSION['flash'] = ['message' => $message, 'type' => $type];
}

function getFlash(): ?array
{
    if (!isset($_SESSION['flash'])) {
        return null;
    }
    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']);
    return $flash;
}

function notFound(): void
{
    http_response_code(404);
    render('404', ['pages' => []]);
    exit;
}

function handlePosts(PDO $pdo, string $method): void
{
    if ($method === 'POST') {
        $action = $_POST['action'] ?? 'create';
        $id = (int)($_POST['id'] ?? 0);
        $title = trim($_POST['title'] ?? '');
        $slug = slugify($_POST['slug'] ?? $title);
        $excerpt = trim($_POST['excerpt'] ?? '');
        $content = trim($_POST['content'] ?? '');
        $now = date(DATE_ATOM);

        if ($action === 'delete' && $id) {
            $stmt = $pdo->prepare('DELETE FROM posts WHERE id = ?');
            $stmt->execute([$id]);
            setFlash('Beitrag wurde gelöscht.', 'success');
            header('Location: ' . url('admin/posts'));
            exit;
        }

        if ($title === '' || $content === '') {
            setFlash('Titel und Inhalt sind erforderlich.', 'error');
            header('Location: ' . url('admin/posts'));
            exit;
        }

        if ($id) {
            $stmt = $pdo->prepare('UPDATE posts SET title = ?, slug = ?, excerpt = ?, content = ?, updated_at = ? WHERE id = ?');
            $stmt->execute([$title, $slug, $excerpt, $content, $now, $id]);
            setFlash('Beitrag aktualisiert.', 'success');
        } else {
            $stmt = $pdo->prepare('INSERT INTO posts (title, slug, excerpt, content, published_at, updated_at) VALUES (?, ?, ?, ?, ?, ?)');
            $stmt->execute([$title, $slug, $excerpt, $content, $now, $now]);
            setFlash('Beitrag erstellt.', 'success');
        }

        header('Location: ' . url('admin/posts'));
        exit;
    }

    $editId = isset($_GET['id']) ? (int)$_GET['id'] : null;
    $editPost = null;
    if ($editId) {
        $stmt = $pdo->prepare('SELECT * FROM posts WHERE id = ?');
        $stmt->execute([$editId]);
        $editPost = $stmt->fetch();
    }

    renderAdmin('posts/index', [
        'posts' => fetchPosts($pdo, 100),
        'editPost' => $editPost
    ]);
}

function handlePages(PDO $pdo, string $method): void
{
    if ($method === 'POST') {
        $action = $_POST['action'] ?? 'create';
        $id = (int)($_POST['id'] ?? 0);
        $title = trim($_POST['title'] ?? '');
        $slug = slugify($_POST['slug'] ?? $title);
        $content = trim($_POST['content'] ?? '');
        $now = date(DATE_ATOM);

        if ($action === 'delete' && $id) {
            $stmt = $pdo->prepare('DELETE FROM pages WHERE id = ?');
            $stmt->execute([$id]);
            setFlash('Seite wurde gelöscht.', 'success');
            header('Location: ' . url('admin/pages'));
            exit;
        }

        if ($title === '' || $content === '') {
            setFlash('Titel und Inhalt sind erforderlich.', 'error');
            header('Location: ' . url('admin/pages'));
            exit;
        }

        if ($id) {
            $stmt = $pdo->prepare('UPDATE pages SET title = ?, slug = ?, content = ?, updated_at = ? WHERE id = ?');
            $stmt->execute([$title, $slug, $content, $now, $id]);
            setFlash('Seite aktualisiert.', 'success');
        } else {
            $stmt = $pdo->prepare('INSERT INTO pages (title, slug, content, updated_at) VALUES (?, ?, ?, ?)');
            $stmt->execute([$title, $slug, $content, $now]);
            setFlash('Seite erstellt.', 'success');
        }

        header('Location: ' . url('admin/pages'));
        exit;
    }

    $editId = isset($_GET['id']) ? (int)$_GET['id'] : null;
    $editPage = null;
    if ($editId) {
        $stmt = $pdo->prepare('SELECT * FROM pages WHERE id = ?');
        $stmt->execute([$editId]);
        $editPage = $stmt->fetch();
    }

    renderAdmin('pages/index', [
        'pagesList' => fetchPages($pdo),
        'editPage' => $editPage
    ]);
}

function handleGallery(PDO $pdo, string $method): void
{
    if ($method === 'POST') {
        $action = $_POST['action'] ?? 'create';
        $id = (int)($_POST['id'] ?? 0);
        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $imageUrl = trim($_POST['image_url'] ?? '');
        $now = date(DATE_ATOM);
        $imagePath = $imageUrl;

        if (!empty($_FILES['image']['name'])) {
            $imagePath = handleUpload($_FILES['image']);
        }

        if ($action === 'delete' && $id) {
            $stmt = $pdo->prepare('DELETE FROM gallery_items WHERE id = ?');
            $stmt->execute([$id]);
            setFlash('Galerie-Eintrag gelöscht.', 'success');
            header('Location: ' . url('admin/gallery'));
            exit;
        }

        if ($title === '') {
            setFlash('Titel ist erforderlich.', 'error');
            header('Location: ' . url('admin/gallery'));
            exit;
        }

        if ($id) {
            $stmt = $pdo->prepare('UPDATE gallery_items SET title = ?, description = ?, image_path = COALESCE(?, image_path) WHERE id = ?');
            $stmt->execute([$title, $description, $imagePath ?: null, $id]);
            setFlash('Galerie-Eintrag aktualisiert.', 'success');
        } else {
            $stmt = $pdo->prepare('INSERT INTO gallery_items (title, description, image_path, created_at) VALUES (?, ?, ?, ?)');
            $stmt->execute([$title, $description, $imagePath, $now]);
            setFlash('Galerie-Eintrag erstellt.', 'success');
        }

        header('Location: ' . url('admin/gallery'));
        exit;
    }

    $editId = isset($_GET['id']) ? (int)$_GET['id'] : null;
    $editItem = null;
    if ($editId) {
        $stmt = $pdo->prepare('SELECT * FROM gallery_items WHERE id = ?');
        $stmt->execute([$editId]);
        $editItem = $stmt->fetch();
    }

    renderAdmin('gallery/index', [
        'items' => fetchGalleryItems($pdo),
        'editItem' => $editItem
    ]);
}

function handleGeneticsAdmin(PDO $pdo, string $method): void
{
    if ($method === 'POST') {
        $action = $_POST['action'] ?? 'create-species';

        if (str_starts_with($action, 'delete-')) {
            $type = explode('-', $action, 2)[1];
            if ($type === 'species') {
                $id = (int)($_POST['id'] ?? 0);
                $stmt = $pdo->prepare('DELETE FROM species WHERE id = ?');
                $stmt->execute([$id]);
                setFlash('Art wurde gelöscht.', 'success');
            } elseif ($type === 'gene') {
                $id = (int)($_POST['id'] ?? 0);
                $stmt = $pdo->prepare('DELETE FROM genes WHERE id = ?');
                $stmt->execute([$id]);
                setFlash('Gen wurde gelöscht.', 'success');
            }
            header('Location: ' . url('admin/genetics'));
            exit;
        }

        if ($action === 'save-species') {
            $id = (int)($_POST['id'] ?? 0);
            $slug = slugify($_POST['slug'] ?? ($_POST['common_name'] ?? ''));
            $latin = trim($_POST['latin_name'] ?? '');
            $common = trim($_POST['common_name'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $habitat = trim($_POST['habitat'] ?? '');
            $careNotes = trim($_POST['care_notes'] ?? '');

            if ($common === '' || $latin === '' || $description === '') {
                setFlash('Bitte alle Pflichtfelder ausfüllen.', 'error');
                header('Location: ' . url('admin/genetics', ['tab' => 'species']));
                exit;
            }

            if ($id) {
                $stmt = $pdo->prepare('UPDATE species SET slug = ?, latin_name = ?, common_name = ?, description = ?, habitat = ?, care_notes = ? WHERE id = ?');
                $stmt->execute([$slug, $latin, $common, $description, $habitat, $careNotes, $id]);
                setFlash('Art aktualisiert.', 'success');
            } else {
                $stmt = $pdo->prepare('INSERT INTO species (slug, latin_name, common_name, description, habitat, care_notes) VALUES (?, ?, ?, ?, ?, ?)');
                $stmt->execute([$slug, $latin, $common, $description, $habitat, $careNotes]);
                setFlash('Art hinzugefügt.', 'success');
            }

            header('Location: ' . url('admin/genetics', ['tab' => 'species']));
            exit;
        }

        if ($action === 'save-gene') {
            $id = (int)($_POST['id'] ?? 0);
            $speciesId = (int)($_POST['species_id'] ?? 0);
            $name = trim($_POST['name'] ?? '');
            $inheritance = trim($_POST['inheritance'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $visuals = [
                'dominant' => trim($_POST['visual_dominant'] ?? ''),
                'heterozygous' => trim($_POST['visual_heterozygous'] ?? ''),
                'recessive' => trim($_POST['visual_recessive'] ?? '')
            ];

            if ($speciesId === 0 || $name === '' || $inheritance === '') {
                setFlash('Bitte alle Pflichtfelder für Gene ausfüllen.', 'error');
                header('Location: ' . url('admin/genetics', ['tab' => 'genes']));
                exit;
            }

            $visualsJson = json_encode($visuals, JSON_UNESCAPED_UNICODE);

            if ($id) {
                $stmt = $pdo->prepare('UPDATE genes SET species_id = ?, name = ?, inheritance = ?, description = ?, visuals = ? WHERE id = ?');
                $stmt->execute([$speciesId, $name, $inheritance, $description, $visualsJson, $id]);
                setFlash('Gen aktualisiert.', 'success');
            } else {
                $stmt = $pdo->prepare('INSERT INTO genes (species_id, name, inheritance, description, visuals) VALUES (?, ?, ?, ?, ?)');
                $stmt->execute([$speciesId, $name, $inheritance, $description, $visualsJson]);
                setFlash('Gen hinzugefügt.', 'success');
            }

            header('Location: ' . url('admin/genetics', ['tab' => 'genes']));
            exit;
        }
    }

    $editSpeciesId = isset($_GET['species_id']) ? (int)$_GET['species_id'] : null;
    $editSpecies = null;
    if ($editSpeciesId) {
        $stmt = $pdo->prepare('SELECT * FROM species WHERE id = ?');
        $stmt->execute([$editSpeciesId]);
        $editSpecies = $stmt->fetch();
    }

    $editGeneId = isset($_GET['gene_id']) ? (int)$_GET['gene_id'] : null;
    $editGene = null;
    if ($editGeneId) {
        $stmt = $pdo->prepare('SELECT * FROM genes WHERE id = ?');
        $stmt->execute([$editGeneId]);
        $editGene = $stmt->fetch();
        if ($editGene) {
            $editGene['visuals'] = $editGene['visuals'] ? json_decode($editGene['visuals'], true) : ['dominant' => '', 'heterozygous' => '', 'recessive' => ''];
        }
    }

    renderAdmin('genetics/index', [
        'species' => fetchSpecies($pdo),
        'genes' => fetchAllGenes($pdo),
        'editSpecies' => $editSpecies,
        'editGene' => $editGene,
        'activeTab' => $_GET['tab'] ?? 'species'
    ]);
}

function fetchAllGenes(PDO $pdo): array
{
    $stmt = $pdo->query('SELECT g.*, s.common_name FROM genes g JOIN species s ON s.id = g.species_id ORDER BY s.common_name, g.name');
    $genes = $stmt->fetchAll();
    foreach ($genes as &$gene) {
        $gene['visuals'] = $gene['visuals'] ? json_decode($gene['visuals'], true) : [];
    }
    return $genes;
}

function handleGeneticsCalculator(PDO $pdo, string $method): void
{
    $speciesList = fetchSpecies($pdo);
    $selectedSpeciesId = isset($_GET['species_id']) ? (int)$_GET['species_id'] : ($speciesList[0]['id'] ?? null);
    $selectedSpecies = null;
    $genes = [];
    $calculation = [
        'perGene' => [],
        'combinations' => []
    ];
    $parentASelections = [];
    $parentBSelections = [];

    if ($selectedSpeciesId) {
        foreach ($speciesList as $sp) {
            if ((int)$sp['id'] === $selectedSpeciesId) {
                $selectedSpecies = $sp;
                break;
            }
        }
        if ($selectedSpecies) {
            $genes = fetchGenesForSpecies($pdo, $selectedSpeciesId);
        }
    }

    if ($method === 'POST' && $selectedSpecies) {
        $parentASelections = $_POST['parent_a'] ?? [];
        $parentBSelections = $_POST['parent_b'] ?? [];
        $calculation = computeGenetics($genes, $parentASelections, $parentBSelections);
    }

    render('genetics/calculator', [
        'speciesList' => $speciesList,
        'selectedSpecies' => $selectedSpecies,
        'genes' => $genes,
        'calculation' => $calculation,
        'parentA' => $parentASelections,
        'parentB' => $parentBSelections,
        'pages' => fetchPages($pdo)
    ]);
}

function computeGenetics(array $genes, array $parentA, array $parentB): array
{
    $perGene = [];
    $combinations = [
        [
            'probability' => 1,
            'descriptors' => [],
            'genotypes' => []
        ]
    ];

    foreach ($genes as $gene) {
        $geneId = $gene['id'];
        $inheritance = $gene['inheritance'];
        $visuals = $gene['visuals'] ?? [];

        $a = $parentA[$geneId] ?? 'heterozygous';
        $b = $parentB[$geneId] ?? 'heterozygous';

        $allelesA = genotypeToAlleles($inheritance, $a);
        $allelesB = genotypeToAlleles($inheritance, $b);

        $punnett = [];
        foreach ($allelesA as $alleleA) {
            foreach ($allelesB as $alleleB) {
                $genotype = normalizeGenotype($alleleA . $alleleB);
                $punnett[$genotype] = ($punnett[$genotype] ?? 0) + 1;
            }
        }

        $total = max(array_sum($punnett), 1);
        $variants = [];
        foreach ($punnett as $genotype => $count) {
            $phenotype = matchPhenotype($inheritance, $genotype, $visuals);
            $fraction = $count / $total;
            $variants[] = [
                'genotype' => $genotype,
                'phenotype' => $phenotype,
                'probability' => round($fraction * 100, 2),
                'fraction' => $fraction,
                'descriptor' => descriptorForVariant($gene, $genotype, $phenotype)
            ];
        }

        $perGene[] = [
            'name' => $gene['name'],
            'inheritance' => $inheritance,
            'variants' => array_map(function ($variant) {
                return [
                    'genotype' => $variant['genotype'],
                    'phenotype' => $variant['phenotype'],
                    'probability' => $variant['probability']
                ];
            }, $variants)
        ];

        $newCombinations = [];
        foreach ($combinations as $combo) {
            foreach ($variants as $variant) {
                $descriptors = $combo['descriptors'];
                if ($variant['descriptor'] !== '') {
                    $descriptors[] = $variant['descriptor'];
                }
                $newCombinations[] = [
                    'probability' => $combo['probability'] * $variant['fraction'],
                    'descriptors' => $descriptors,
                    'genotypes' => $combo['genotypes'] + [$gene['name'] => $variant['genotype']]
                ];
            }
        }
        $combinations = $newCombinations;
    }

    $aggregated = [];
    foreach ($combinations as $combo) {
        $descriptors = $combo['descriptors'];
        usort($descriptors, 'sortDescriptors');
        $key = implode('||', $descriptors);
        if (!isset($aggregated[$key])) {
            $aggregated[$key] = [
                'probability' => 0,
                'descriptors' => $descriptors
            ];
        }
        $aggregated[$key]['probability'] += $combo['probability'];
    }

    $combined = [];
    foreach ($aggregated as $data) {
        $percentage = round($data['probability'] * 100, 2);
        $label = formatDescriptorLabel($data['descriptors']);
        $combined[] = [
            'label' => $label,
            'probability' => $percentage
        ];
    }

    usort($combined, function ($a, $b) {
        return $b['probability'] <=> $a['probability'];
    });

    return [
        'perGene' => $perGene,
        'combinations' => $combined
    ];
}

function sortDescriptors(string $a, string $b): int
{
    $weight = function (string $value): int {
        $lower = strtolower($value);
        if (str_starts_with($lower, 'het ')) {
            return 1;
        }
        if (str_contains($lower, 'normal')) {
            return 2;
        }
        return 0;
    };

    $weightA = $weight($a);
    $weightB = $weight($b);
    if ($weightA === $weightB) {
        return strnatcasecmp($a, $b);
    }
    return $weightA <=> $weightB;
}

function formatDescriptorLabel(array $descriptors): string
{
    if (empty($descriptors)) {
        return 'Normal';
    }

    $visuals = [];
    $hets = [];
    $others = [];

    foreach ($descriptors as $descriptor) {
        $lower = strtolower($descriptor);
        if (str_starts_with($lower, 'het ')) {
            $hets[] = $descriptor;
        } elseif (str_contains($lower, 'normal')) {
            $others[] = ucfirst($descriptor);
        } else {
            $visuals[] = $descriptor;
        }
    }

    $parts = array_filter(array_merge($visuals, $hets, $others));
    $label = trim(implode(' ', $parts));

    return $label !== '' ? $label : 'Normal';
}

function descriptorForVariant(array $gene, string $genotype, string $phenotype): string
{
    $inheritance = $gene['inheritance'];
    $name = $gene['name'];
    $phenotype = trim($phenotype);
    $phenotypeLower = strtolower($phenotype);

    if ($inheritance === 'recessive') {
        return match ($genotype) {
            'aa' => $phenotype !== '' ? $phenotype : $name,
            'Aa' => 'het ' . $name,
            default => ''
        };
    }

    if ($inheritance === 'co-dominant') {
        if ($genotype === 'AA') {
            return $phenotype !== '' ? $phenotype : ('Super ' . $name);
        }
        if ($genotype === 'Aa') {
            return $phenotype !== '' ? $phenotype : $name;
        }
        return '';
    }

    if ($genotype === 'AA' || $genotype === 'Aa') {
        if ($phenotype === '' || str_contains($phenotypeLower, 'normal')) {
            return $name;
        }
        return $phenotype;
    }

    return '';
}

function genotypeToAlleles(string $inheritance, string $state): array
{
    switch ($inheritance) {
        case 'recessive':
            return match ($state) {
                'dominant' => ['A', 'A'],
                'recessive' => ['a', 'a'],
                default => ['A', 'a']
            };
        case 'co-dominant':
        case 'dominant':
            return match ($state) {
                'dominant' => ['A', 'A'],
                'recessive' => ['a', 'a'],
                default => ['A', 'a']
            };
        default:
            return ['A', 'a'];
    }
}

function normalizeGenotype(string $genotype): string
{
    $chars = str_split($genotype);
    sort($chars);
    return implode('', $chars);
}

function matchPhenotype(string $inheritance, string $genotype, array $visuals): string
{
    $visuals += ['dominant' => 'Normal', 'heterozygous' => 'Het', 'recessive' => 'Visual'];

    return match ($inheritance) {
        'recessive' => match ($genotype) {
            'AA' => $visuals['dominant'],
            'Aa' => $visuals['heterozygous'],
            'aa' => $visuals['recessive'],
            default => $visuals['heterozygous']
        },
        'co-dominant' => match ($genotype) {
            'AA' => $visuals['dominant'],
            'Aa' => $visuals['heterozygous'],
            'aa' => $visuals['recessive'],
            default => $visuals['heterozygous']
        },
        default => match ($genotype) {
            'AA' => $visuals['dominant'],
            'Aa' => $visuals['dominant'],
            'aa' => $visuals['recessive'],
            default => $visuals['dominant']
        }
    };
}

function slugify(string $value): string
{
    $transliterated = iconv('UTF-8', 'ASCII//TRANSLIT', $value);
    if ($transliterated !== false) {
        $value = $transliterated;
    }
    $value = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $value));
    $value = trim($value, '-');
    return $value ?: uniqid('item-');
}

function handleUpload(array $file): string
{
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return '';
    }

    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = uniqid('upload_', true) . ($ext ? '.' . $ext : '');
    $destination = UPLOAD_PATH . '/' . $filename;

    if (!move_uploaded_file($file['tmp_name'], $destination)) {
        setFlash('Upload fehlgeschlagen.', 'error');
        return '';
    }

    return asset('uploads/' . $filename);
}

function genotypeOptions(string $inheritance): array
{
    if ($inheritance === 'recessive') {
        return [
            'dominant' => 'Homozygot dominant (Normal)',
            'heterozygous' => 'Heterozygot (Träger)',
            'recessive' => 'Homozygot rezessiv (Visual)'
        ];
    }

    if ($inheritance === 'co-dominant') {
        return [
            'dominant' => 'Homozygot (Super-Form)',
            'heterozygous' => 'Heterozygot (Co-Dom)',
            'recessive' => 'Normal'
        ];
    }

    return [
        'dominant' => 'Dominant',
        'heterozygous' => 'Träger',
        'recessive' => 'Normal'
    ];
}
