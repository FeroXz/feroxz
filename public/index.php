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
            'gallery' => fetchGalleryItems($pdo, 6)
        ]);
        break;
    case 'post':
        $slug = $_GET['slug'] ?? '';
        $post = findPostBySlug($pdo, $slug);
        if (!$post) {
            notFound();
        }
        render('post', ['post' => $post]);
        break;
    case 'page':
        $slug = $_GET['slug'] ?? '';
        $page = findPageBySlug($pdo, $slug);
        if (!$page) {
            notFound();
        }
        render('page', ['page' => $page]);
        break;
    case 'gallery':
        render('gallery', [
            'items' => fetchGalleryItems($pdo)
        ]);
        break;
    case 'animals':
        $showcased = fetchShowcasedAnimals($pdo);
        $genesBySpecies = [];
        foreach ($showcased as $animal) {
            $speciesId = (int)$animal['species_id'];
            if (!isset($genesBySpecies[$speciesId])) {
                $genesBySpecies[$speciesId] = fetchGenesForSpecies($pdo, $speciesId);
            }
        }
        render('animals/index', [
            'animals' => $showcased,
            'genesBySpecies' => $genesBySpecies
        ]);
        break;
    case 'genetics':
        render('genetics/index', [
            'species' => fetchSpecies($pdo)
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
            'genes' => $genes
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
    case 'account/animals':
        $accountUser = requireLogin($pdo);
        handleAccountAnimals($pdo, $accountUser);
        break;
    case 'admin':
        requireAdmin($pdo);
        renderAdmin('dashboard', [
            'postCount' => countAll($pdo, 'posts'),
            'pageCount' => countAll($pdo, 'pages'),
            'galleryCount' => countAll($pdo, 'gallery_items'),
            'speciesCount' => countAll($pdo, 'species'),
            'animalCount' => countAll($pdo, 'animals')
        ]);
        break;
    case 'admin/posts':
        requireAdmin($pdo, 'posts');
        handlePosts($pdo, $method);
        break;
    case 'admin/pages':
        requireAdmin($pdo, 'pages');
        handlePages($pdo, $method);
        break;
    case 'admin/gallery':
        requireAdmin($pdo, 'gallery');
        handleGallery($pdo, $method);
        break;
    case 'admin/genetics':
        requireAdmin($pdo, 'genetics');
        handleGeneticsAdmin($pdo, $method);
        break;
    case 'admin/animals':
        requireAdmin($pdo, 'animals');
        handleAnimals($pdo, $method);
        break;
    case 'admin/users':
        requireAdmin($pdo, 'users');
        handleUsers($pdo, $method);
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
        role TEXT NOT NULL DEFAULT "admin",
        permissions TEXT,
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
        parent_id INTEGER,
        menu_order INTEGER NOT NULL DEFAULT 0,
        is_visible INTEGER NOT NULL DEFAULT 1,
        updated_at TEXT NOT NULL,
        FOREIGN KEY (parent_id) REFERENCES pages(id) ON DELETE SET NULL
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

    $pdo->exec('CREATE TABLE IF NOT EXISTS animals (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        species_id INTEGER NOT NULL,
        owner_id INTEGER,
        name TEXT NOT NULL,
        slug TEXT UNIQUE,
        age TEXT,
        origin TEXT,
        genetics_notes TEXT,
        special_notes TEXT,
        is_showcased INTEGER NOT NULL DEFAULT 0,
        is_private INTEGER NOT NULL DEFAULT 0,
        created_at TEXT NOT NULL,
        updated_at TEXT NOT NULL,
        FOREIGN KEY (species_id) REFERENCES species(id) ON DELETE CASCADE,
        FOREIGN KEY (owner_id) REFERENCES users(id) ON DELETE SET NULL
    )');

    $pdo->exec('CREATE TABLE IF NOT EXISTS animal_genotypes (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        animal_id INTEGER NOT NULL,
        gene_id INTEGER NOT NULL,
        genotype TEXT NOT NULL,
        FOREIGN KEY (animal_id) REFERENCES animals(id) ON DELETE CASCADE,
        FOREIGN KEY (gene_id) REFERENCES genes(id) ON DELETE CASCADE,
        UNIQUE (animal_id, gene_id)
    )');

    $pdo->exec('CREATE TABLE IF NOT EXISTS animal_images (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        animal_id INTEGER NOT NULL,
        image_path TEXT NOT NULL,
        created_at TEXT NOT NULL,
        FOREIGN KEY (animal_id) REFERENCES animals(id) ON DELETE CASCADE
    )');

    ensureColumn($pdo, 'animals', 'is_showcased', 'ALTER TABLE animals ADD COLUMN is_showcased INTEGER NOT NULL DEFAULT 0');
    ensureColumn($pdo, 'animals', 'owner_id', 'ALTER TABLE animals ADD COLUMN owner_id INTEGER REFERENCES users(id)');
    ensureColumn($pdo, 'animals', 'is_private', 'ALTER TABLE animals ADD COLUMN is_private INTEGER NOT NULL DEFAULT 0');
    ensureColumn($pdo, 'users', 'role', 'ALTER TABLE users ADD COLUMN role TEXT NOT NULL DEFAULT "admin"');
    ensureColumn($pdo, 'users', 'permissions', 'ALTER TABLE users ADD COLUMN permissions TEXT');
    ensureColumn($pdo, 'pages', 'parent_id', 'ALTER TABLE pages ADD COLUMN parent_id INTEGER');
    ensureColumn($pdo, 'pages', 'menu_order', 'ALTER TABLE pages ADD COLUMN menu_order INTEGER NOT NULL DEFAULT 0');
    ensureColumn($pdo, 'pages', 'is_visible', 'ALTER TABLE pages ADD COLUMN is_visible INTEGER NOT NULL DEFAULT 1');

    $pdo->exec('UPDATE pages SET menu_order = id WHERE COALESCE(menu_order, 0) = 0');
    $pdo->exec('UPDATE users SET role = COALESCE(role, "admin")');

    if ((int)$pdo->query('SELECT COUNT(*) FROM users')->fetchColumn() === 0) {
        $stmt = $pdo->prepare('INSERT INTO users (username, password_hash, role, permissions, created_at) VALUES (?, ?, ?, ?, ?)');
        $stmt->execute([
            'admin',
            password_hash('12345678', PASSWORD_DEFAULT),
            'admin',
            null,
            date(DATE_ATOM)
        ]);
    }

    if ((int)$pdo->query('SELECT COUNT(*) FROM posts')->fetchColumn() === 0) {
        seedContent($pdo);
    }

    seedGeneticsData($pdo);
    ensureCareGuideContent($pdo);
}

function ensureCareGuideContent(PDO $pdo): void
{
    $stmt = $pdo->prepare('SELECT id, content FROM pages WHERE slug = ?');
    $stmt->execute(['pflegeleitfaden']);
    $existing = $stmt->fetch();
    $now = date(DATE_ATOM);
    $content = careGuideContent();

    if ($existing) {
        $currentContent = trim((string)($existing['content'] ?? ''));
        $length = function_exists('mb_strlen') ? mb_strlen($currentContent, 'UTF-8') : strlen($currentContent);
        if ($length < 1200 || str_contains($currentContent, 'Ein artgerechtes Terrarium beginnt')) {
            $update = $pdo->prepare('UPDATE pages SET content = ?, updated_at = ? WHERE id = ?');
            $update->execute([$content, $now, $existing['id']]);
        }
        return;
    }

    $menuOrder = nextMenuOrder($pdo);
    $insert = $pdo->prepare('INSERT INTO pages (title, slug, content, updated_at, parent_id, menu_order, is_visible) VALUES (?, ?, ?, ?, NULL, ?, 1)');
    $insert->execute(['Pflegeleitfaden', 'pflegeleitfaden', $content, $now, $menuOrder]);
}

function careGuideContent(): string
{
    $text = <<<'TEXT'
Pflegeleitfaden für Pogona vitticeps und Heterodon nasicus

Allgemeine Vorbereitung
-----------------------
Bevor Tiere einziehen, müssen grundlegende Rahmenbedingungen stehen: Ein stabiler Aufstellort, Zugang zu Stromkreisen mit ausreichender Absicherung, Zeitschaltuhren für Beleuchtung, ein Vorrat an Futtertieren und Grünfutter sowie ein veterinärmedizinischer Ansprechpartner, der Reptilien behandelt. Eine Quarantänezone (separater Raum oder zumindest ein isoliertes Becken) ist Pflicht, um neue Tiere mindestens 6 Wochen zu beobachten. Führe ein Haltungsbuch, in dem Gewicht, Futter, Häutungen, Besonderheiten und tierärztliche Maßnahmen dokumentiert werden. Dies erleichtert langfristige Gesundheitskontrolle und ist bei genetischen Projekten unerlässlich.


Bartagame (Pogona vitticeps)
---------------------------
Steckbrief
~~~~~~~~~~
- Herkunft: Halbwüsten, Buschland und lichte Wälder Ostaustraliens
- Aktivitätszeit: tagaktiv mit Dämmerungsphasen
- Endgröße: 45–60 cm Gesamtlänge, Weibchen meist etwas kleiner
- Lebenserwartung: 10–15 Jahre bei optimaler Pflege
- Sozialverhalten: Einzelhaltung empfehlenswert; Gruppenhaltung nur mit viel Platz und dauerhaftem Monitoring

Terrarium & Technik
~~~~~~~~~~~~~~~~~~~
- Mindestmaß für ein adultes Tier: 150 × 80 × 80 cm (L × B × H); für Paare/Gruppe deutlich größer (mind. 200 × 100 × 100 cm).
- Material: OSB oder PVC mit großflächiger Belüftung, Frontscheiben zum Öffnen. Rück- und Seitenwände strukturieren, um Kletterflächen und Sichtschutz zu schaffen.
- Bodengrund: Lehm-Sand-Gemisch (ca. 70 % Spielsand, 30 % Lehm), 8–15 cm hoch, punktuell 20 cm zum Graben. Für Eiablage box mit feuchtem Sand-Lehm.
- Strukturierung: Korkröhren, Äste mit rauer Oberfläche, Steinplateaus, erhöhte Sonnenplattform, stabile Höhlen. Schatten- und Rückzugsbereiche einplanen.
- Reinigung: Täglich Kot entfernen, wöchentlich Spot-Cleaning, alle 3–4 Monate Teilsubstrat austauschen, jährlich Grundreinigung.

Klima & Beleuchtung
~~~~~~~~~~~~~~~~~~~
- Temperatur: Tag 28–34 °C, Sonnenplatz 42–48 °C, kühlere Zone 24–26 °C. Nachtabsenkung auf 20–22 °C.
- Heiztechnik: Halogen-Metalldampflampe (70–150 W) über Sonnenplatz, zusätzliche Halogenstrahler für Wärmeinseln, ggf. Heizmatte unter Steinplateau für Nacht (nur mit Thermostat).
- UVB: Vollspektrum-Metalldampflampe (z. B. Solar Raptor/Arcadia) 10–12 h täglich. Röhren (T5 HO) ergänzen das Flächenlicht, UVB-Röhren jährlich wechseln.
- Beleuchtungszyklus: Sommer 12–13 h, Winterruhe (8–12 Wochen) mit schrittweiser Reduktion auf 8 h und Temperaturen von 18–20 °C. Während Winterruhe nur Tageslicht, keine Fütterung.
- Luftfeuchtigkeit: 30–45 % tagsüber, kurze Anhebung auf 50 % durch Sprühen am Morgen fördert Häutung.

Ernährung & Supplemente
~~~~~~~~~~~~~~~~~~~~~~~
- Grundfutter: 60–70 % pflanzlich (Wildkräuter wie Löwenzahn, Wegerich, Endivie, Rucola, Hibiskusblüten). Gemüse wie Kürbis, Zucchini, Karotten geraspelt. Obst nur selten als Leckerli.
- Proteinquellen: Heuschrecken, Schaben, Heimchen, Grillen, gelegentlich Würmer (Morio, Phoenix). Juvenile Tiere täglich kleine Portionen, Adulte 2–3× pro Woche.
- Supplemente: Calcium ohne D3 bei jeder Fütterung über Insekten stäuben, Vitaminpräparat mit D3 1–2× pro Woche (juvenile) bzw. alle 10–14 Tage (adulte). Sepiaschale als freie Calciumquelle anbieten.
- Wasser: Flache Schale täglich frisch, zusätzlich Sprühnebel zur Flüssigkeitsaufnahme.

Handling & Enrichment
~~~~~~~~~~~~~~~~~~~~~
- An Berührungen langsam gewöhnen, nur ruhige Bewegungen. Hände vor Kontakt wärmen, da kalte Hände Stress bedeuten.
- Freiläufe nur in gesichertem, warmem Raum (mind. 26 °C). UVB-Licht bereitstellen, damit Thermoregulation möglich bleibt.
- Beschäftigung: Futterpflanzen in verschiedenen Höhen platzieren, Insekten aus Futtertuben entlassen, Kletterrouten umgestalten.

Gesundheitsmanagement
~~~~~~~~~~~~~~~~~~~~~
- Gewichtskontrolle: Wöchentlich bei Jungtieren, monatlich bei Adulten. Gewichtsverlust >10 % erfordert Tierarzt.
- Häutung: Unterstützen durch feuchte Häutungshilfe (Badeschale, Sprühnebel). Verbleibende Hautreste vorsichtig mit lauwarmem Wasser lösen.
- Häufige Probleme: Metabolische Knochenkrankheit (durch UVB-/Calcium-Mangel), Parasiten (regelmäßige Kotproben), Hepatische Lipidose (durch Überfütterung, fehlende Winterruhe). Frühzeitige Diagnose durch reptilienkundigen Tierarzt.
- Fortpflanzung: Weibchen benötigen Eiablagebox (45 × 30 × 25 cm, feuchtes Sand-Lehm-Gemisch). Nach Eiablage Calcium- und Flüssigkeitszufuhr erhöhen.


Hakennasennatter (Heterodon nasicus)
------------------------------------
Steckbrief
~~~~~~~~~~
- Herkunft: Prärien, sandige Ebenen und offene Wälder Mittel- und Nordamerikas.
- Aktivitätszeit: tag- bis dämmerungsaktiv, gräbt häufig.
- Größe: 45–90 cm, Männchen deutlich kleiner als Weibchen.
- Lebenserwartung: 12–18 Jahre.
- Charakter: neugierig, selten bissig; typische Drohgebärde mit Scheinangriff.

Terrarium & Bodengrund
~~~~~~~~~~~~~~~~~~~~~~
- Mindestmaß: 100 × 50 × 50 cm für adulte Weibchen, Männchen können etwas kleiner, mehr Platz ist aber immer von Vorteil.
- Bodengrund: Mischung aus grabfähigem Sand, Lehm und etwas Kokoshumus (Verhältnis 50/30/20). Schichtdicke 10–15 cm, damit Röhren und Tunnel stabil bleiben.
- Einrichtung: Mehrere Verstecke (Korkhöhlen, halbe Baumrinden), flache Steine unter Wärmequelle, robuste Pflanzen (Sansevieria, künstliche Sukkulenten), Wasserbecken mit flachem Einstieg.
- Sicherheitsfaktor: Terrarium ausbruchssicher verschließen, da Hakennattern kräftig drücken können.

Klima & Beleuchtung
~~~~~~~~~~~~~~~~~~~
- Temperaturgradient: Warmbereich 30–32 °C, Sonnenplatz bis 35 °C, kühle Seite 24–26 °C. Nachtabsenkung auf 20–22 °C.
- Heiztechnik: Spotstrahler (35–50 W) plus Wärmematte oder Heizkabel unter einer Steinplatte, alles über Thermostat sichern.
- Luftfeuchte: 40–55 % tagsüber, leichtes Anheben auf 60 % während Häutung. Eine feuchte Höhle mit Sphagnum oder feuchtem Moos bereitstellen.
- Beleuchtung: Obwohl viele Halter nur Raumlicht nutzen, profitieren Hakennattern von 10–12 h Tageslicht (LED- oder T5-Röhre). UVB mit geringer Intensität (2–5 %) fördert Vitamin-D-Synthese, insbesondere bei subadulten Tieren.

Ernährung
~~~~~~~~~
- Hauptfutter: Frostmäuse in passender Größe (1–1,5× Körperumfang). Jungtiere alle 4–5 Tage, adulte Weibchen wöchentlich, Männchen alle 10–14 Tage.
- Abwechslung: Küken, kleine Ratten, optional in Fisch aromatisierte Beute bei Futterverweigerung. Weichsinnespezies wie Reptilinks können Abwechslung bieten.
- Vorbereitung: Beute vollständig auf Körpertemperatur erwärmen (ca. 35 °C). Mit Pinzette anbieten, bei zögerlichen Tieren leicht „zittern“.
- Supplemente: Calcium-/Vitaminpräparate normalerweise nicht nötig, solange vollständige Beutetiere gefüttert werden. Tragende Weibchen profitieren von zusätzlichem Calcium.

Verhalten & Handling
~~~~~~~~~~~~~~~~~~~~
- Hakennattern stellen sich oft tot (Schreckstarre) oder blähen sich auf. Nicht provozieren, Tier in Ruhe lassen, bis es sich beruhigt.
- Handling auf wenige Minuten beschränken, Hände stützen den Körper vollständig. Nach Fütterung 48 h Ruhe gewähren.
- Enrichment: Verstecke regelmäßig umstellen, Futter verstecken, Grabetappen anfeuchten, um neue Tunnelstrukturen anzubieten.

Gesundheit & Prophylaxe
~~~~~~~~~~~~~~~~~~~~~~~
- Parasitenkontrolle: Kotproben halbjährlich untersuchen lassen. Viele Hakennattern tragen Oxyuren; bei positivem Befund gezielt behandeln.
- Atemwegsinfekte: Entstehen durch zu niedrige Temperaturen oder hohe Feuchte. Symptome: Pfeifgeräusche, offene Maulatmung, Bläschen. Sofort Tierarzt aufsuchen.
- Häutung: Bei Problemen Schlangensauna (Lock&Lock-Box mit lauwarmem, feuchtem Küchenpapier) für 30 Minuten anbieten.
- Fortpflanzung: Paarung im Frühjahr nach leichten Winterkühlung (8–10 Wochen bei 12–15 °C). Eiablagebox mit feuchtem Torf-Sand-Gemisch bereitstellen. Inkubation bei 27–29 °C, Schlupf nach 50–60 Tagen.


Fortgeschrittene Pflegeschwerpunkte
-----------------------------------
Saisonale Planung
~~~~~~~~~~~~~~~~~
- Plane Winterruhe/Winterkühlung langfristig: Futter reduzieren, Beleuchtung und Temperaturen über 2–3 Wochen senken, nach Ruhephase langsam hochfahren. Während der Ruhephase Tiere wöchentlich kontrollieren und Wasser anbieten.

Hygiene & Biosicherheit
~~~~~~~~~~~~~~~~~~~~~~~
- Jedes Terrarium mit eigenem Arbeitsbesteck ausstatten (Pinzetten, Schalen). Desinfektionsmittel auf Quartbasis oder F10 verwenden. Nach jedem Tierkontakt Hände gründlich waschen oder Handschuhe wechseln.
- Neue Tiere strikt in Quarantäne halten: separates Inventar, Handschuhe, tägliche Kontrolle, dreifache Kotproben im Abstand von 14 Tagen, ggf. PCR auf Nidoviren und Adenoviren bei Bartagamen.

Dokumentation & Genetik
~~~~~~~~~~~~~~~~~~~~~~
- Für Zuchtprojekte Stammbäume, Genotypen und Paarungen nachvollziehbar dokumentieren. Fotos der Tiere (Frontal, dorsal, lateral) archivieren. Bei rezessiven Linien den Status (het, pos het, visuell) transparent führen.
- Nutze die im CMS integrierte Tierverwaltung, um jedem Benutzer seine Tiere zuzuordnen. Hinterlege Alter, Herkunft, Besonderheiten und Bilder. So bleibt die Zuchtbuchführung konsistent und deine Tiere lassen sich im Genetik-Rechner korrekt auswählen.

Ernährungsprotokolle & Monitoring
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
- Erstelle Fütterungspläne: Datum, Futterart, Menge, Reaktion des Tieres, Körpergewicht. Dadurch erkennst du Muster bei Futterverweigerung oder saisonalen Schwankungen.
- Wiege Bartagamen und Hakennattern auf der gleichen Waage, um Vergleichbarkeit zu behalten. Notiere Häutungen, Reproduktionszyklen und medizinische Eingriffe.

Notfall-Checkliste
~~~~~~~~~~~~~~~~~~
- Apothekenschrank: Reptilien-Elektrolytlösung, sterile Kochsalzlösung, Verbandsmaterial, Infrarot-Thermometer, Digitalhygrometer, Ersatzlampen.
- Kontakte: Reptilienkundiger Tierarzt, 24/7-Notdienst, Labor für Kotproben.
- Reise-/Urlaubsplanung: Pflegeperson einweisen, schriftliche Checkliste bereitstellen, Videoanleitungen für Fütterung/Technik.

Mit diesem Leitfaden steht eine fundierte Basis für gesunde, langfristig erfolgreiche Haltungen von Pogona vitticeps und Heterodon nasicus zur Verfügung. Ergänze die Informationen durch eigene Beobachtungen und tierärztliche Rücksprache, um individuelle Bedürfnisse deiner Tiere zu berücksichtigen.
TEXT;

    return $text;
}

function tableHasColumn(PDO $pdo, string $table, string $column): bool
{
    $stmt = $pdo->prepare('PRAGMA table_info(' . $table . ')');
    $stmt->execute();
    $columns = $stmt->fetchAll();
    foreach ($columns as $col) {
        if (($col['name'] ?? '') === $column) {
            return true;
        }
    }
    return false;
}

function ensureColumn(PDO $pdo, string $table, string $column, string $alterSql): void
{
    if (!tableHasColumn($pdo, $table, $column)) {
        $pdo->exec($alterSql);
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

}

function seedGeneticsData(PDO $pdo): void
{
    $speciesData = [
        'pogona-vitticeps' => [
            'latin_name' => 'Pogona vitticeps',
            'common_name' => 'Bartagame',
            'description' => 'Die Bartagame zählt zu den beliebtesten Terrarientieren und bietet eine beeindruckende Vielfalt an Farbmorphen. Viele Linien stammen aus gezielten Nachzuchten mit dokumentierter Genetik.',
            'habitat' => 'Trockene Busch- und Steppengebiete Ostaustraliens mit intensiver Sonneneinstrahlung.',
            'care_notes' => 'Tagestemperaturen 28–32 °C mit Sonnenplatz bis 42 °C, UVB-Strahlung, abwechslungsreiche Ernährung aus Pflanzen und Insekten sowie strukturierte Kletter- und Grabmöglichkeiten.',
            'genes' => [
                [
                    'name' => 'Albino',
                    'inheritance' => 'recessive',
                    'description' => 'Amelanistische Linie, die sämtliche schwarzen Pigmente verliert. Die Tiere zeigen rote Augen, warme Gelbtöne und sind lichtempfindlicher – UVB-Versorgung muss behutsam erfolgen.',
                    'visuals' => [
                        'dominant' => 'Normal',
                        'heterozygous' => 'Het Albino',
                        'recessive' => 'Albino (amelanistisch)'
                    ]
                ],
                [
                    'name' => 'Hypomelanistic',
                    'inheritance' => 'recessive',
                    'description' => 'Reduzierter Melaninanteil sorgt für klarere Pastellfarben, glasige Krallen und kontrastarme Zeichnungen. Häufig Grundlage für knallige Designer-Linien.',
                    'visuals' => [
                        'dominant' => 'Normal',
                        'heterozygous' => 'Het Hypomelanistic',
                        'recessive' => 'Hypomelanistic (Hypo)'
                    ]
                ],
                [
                    'name' => 'Translucent',
                    'inheritance' => 'recessive',
                    'description' => 'Teiltransparentes Schuppenkleid mit dunklen, oft bläulich schimmernden Augen. Jungtiere besitzen häufig einen intensiven blauen Bauch, der mit dem Alter aufhellt.',
                    'visuals' => [
                        'dominant' => 'Normal',
                        'heterozygous' => 'Het Translucent',
                        'recessive' => 'Translucent'
                    ]
                ],
                [
                    'name' => 'Leatherback',
                    'inheritance' => 'co-dominant',
                    'description' => 'Co-dominante Schuppenmutation mit reduzierten, glatteren Schuppen. Heterozygote Tiere zeigen kräftigere Farben; homozygote „Silkbacks“ sind komplett glatt und benötigen erhöhte Pflege.',
                    'visuals' => [
                        'dominant' => 'Silkback (glatt)',
                        'heterozygous' => 'Leatherback',
                        'recessive' => 'Normal'
                    ]
                ],
                [
                    'name' => 'Dunner',
                    'inheritance' => 'dominant',
                    'description' => 'Dominantes Muster-Gen, das eine chaotische Zeichnung, rundere Schuppen und eine verstärkte Rückenfärbung erzeugt. Homozygote Tiere wirken intensiver, vererben das Merkmal aber identisch.',
                    'visuals' => [
                        'dominant' => 'Dunner (homozygot)',
                        'heterozygous' => 'Dunner',
                        'recessive' => 'Normal'
                    ]
                ],
                [
                    'name' => 'German Giant',
                    'inheritance' => 'dominant',
                    'description' => 'Aus deutschen Großlinien selektierte Bartagamen, die 20–30 % größer werden und massiger bauen. Fütterung und Platzangebot müssen angepasst werden.',
                    'visuals' => [
                        'dominant' => 'German Giant (XXL)',
                        'heterozygous' => 'German Giant',
                        'recessive' => 'Normalgröße'
                    ]
                ],
                [
                    'name' => 'Zero',
                    'inheritance' => 'recessive',
                    'description' => 'Komplett melanin- und patternlose Bartagamen mit hellem bis grauem Grundton. Oft als Grundlage für Designer-Linien wie Wero genutzt.',
                    'visuals' => [
                        'dominant' => 'Normal',
                        'heterozygous' => 'Het Zero',
                        'recessive' => 'Zero (patternlos)'
                    ]
                ],
                [
                    'name' => 'Witblits',
                    'inheritance' => 'recessive',
                    'description' => 'Rezessive Pastell-Linie mit nahezu weißem Körper und feinem Muster. Kombiniert sich eindrucksvoll mit Hypo- und Translucent-Genetik.',
                    'visuals' => [
                        'dominant' => 'Normal',
                        'heterozygous' => 'Het Witblits',
                        'recessive' => 'Witblits'
                    ]
                ],
                [
                    'name' => 'Wero',
                    'inheritance' => 'recessive',
                    'description' => 'Kombinationslinie aus Zero und Witblits, die reinerbig vollständig weiß erscheint und nur minimale Musterung zulässt.',
                    'visuals' => [
                        'dominant' => 'Normal',
                        'heterozygous' => 'Het Wero',
                        'recessive' => 'Wero (weiß)'
                    ]
                ],
                [
                    'name' => 'Paradox',
                    'inheritance' => 'dominant',
                    'description' => 'Unvorhersehbare Farbsprenkel in ansonsten einfarbigen Linien. Die Mutation wirkt dominant, doch das Ausmaß der Paradox-Flecken variiert stark.',
                    'visuals' => [
                        'dominant' => 'Paradox (stark)',
                        'heterozygous' => 'Paradox',
                        'recessive' => 'Normal'
                    ]
                ],
                [
                    'name' => 'Citrus',
                    'inheritance' => 'dominant',
                    'description' => 'Selektionslinie für intensive Gelb- und Orangetöne. Häufig mit Hypo kombiniert, um satte Citrus-Farben mit hoher Deckkraft zu erzielen.',
                    'visuals' => [
                        'dominant' => 'Citrus (homozygot)',
                        'heterozygous' => 'Citrus',
                        'recessive' => 'Normalfärbung'
                    ]
                ],
                [
                    'name' => 'Red Monster',
                    'inheritance' => 'dominant',
                    'description' => 'Extrem rote Linie, die über viele Generationen auf hohe Rotintensität selektiert wurde. Tiere zeigen besonders kräftige Kopf- und Bartfärbung.',
                    'visuals' => [
                        'dominant' => 'Red Monster (intensiv)',
                        'heterozygous' => 'Red Monster',
                        'recessive' => 'Normal'
                    ]
                ],
                [
                    'name' => 'Sunburst',
                    'inheritance' => 'dominant',
                    'description' => 'Goldgelbe Linie mit starker Kopfzeichnung. Durch Kombination mit Hypo und Leatherback entstehen brilliante Hochglanzfarben.',
                    'visuals' => [
                        'dominant' => 'Sunburst (homozygot)',
                        'heterozygous' => 'Sunburst',
                        'recessive' => 'Normal'
                    ]
                ],
                [
                    'name' => 'Microscale',
                    'inheritance' => 'co-dominant',
                    'description' => 'Verwandt mit Leatherback, erzeugt aber extrem kleine Schuppen. Homozygote Tiere wirken nahezu glatt, behalten aber eine feine Struktur.',
                    'visuals' => [
                        'dominant' => 'Super Microscale',
                        'heterozygous' => 'Microscale',
                        'recessive' => 'Normal'
                    ]
                ]
            ]
        ],
        'heterodon-nasicus' => [
            'latin_name' => 'Heterodon nasicus',
            'common_name' => 'Westliche Hakennatter',
            'description' => 'Die westliche Hakennatter begeistert durch ihre upturned Schnauze, ein ruhiges Temperament und eine Vielzahl rezessiver wie co-dominanter Morphen.',
            'habitat' => 'Prärie- und Halbwüsten Nordamerikas mit lockeren Sandböden für Grabaktivität.',
            'care_notes' => 'Tagsüber 26–29 °C mit Sonnenplatz bis 34 °C, Nachtabsenkung 21–23 °C. Grober Sand-Lehm-Mix zum Wühlen und abwechslungsreiche Fütterung mit Mäusen, gelegentlich Amphibien.',
            'genes' => [
                [
                    'name' => 'Albino',
                    'inheritance' => 'recessive',
                    'description' => 'Fehlendes Melanin sorgt für gelb-rosa Tiere mit rubinroten Augen. Häufig Basis für Coral- und Extreme-Red-Linien.',
                    'visuals' => [
                        'dominant' => 'Normal',
                        'heterozygous' => 'Het Albino',
                        'recessive' => 'Albino'
                    ]
                ],
                [
                    'name' => 'Axanthic',
                    'inheritance' => 'recessive',
                    'description' => 'Unterdrückt gelbe Pigmente und erzeugt silbrig-graue Tiere mit starkem Kontrast. Grundlage für Snow- und Stormtrooper-Projekte.',
                    'visuals' => [
                        'dominant' => 'Normal',
                        'heterozygous' => 'Het Axanthic',
                        'recessive' => 'Axanthic'
                    ]
                ],
                [
                    'name' => 'Lavender',
                    'inheritance' => 'recessive',
                    'description' => 'Pastellfarbene Variante mit lavendelfarbenem Grundton und hellen Augen. Entwickelt sich mit dem Alter zu kühlen Violett- und Graunuancen.',
                    'visuals' => [
                        'dominant' => 'Normal',
                        'heterozygous' => 'Het Lavender',
                        'recessive' => 'Lavender'
                    ]
                ],
                [
                    'name' => 'Toffee Belly',
                    'inheritance' => 'recessive',
                    'description' => 'Sorgt für karamellfarbene Unterseite, warme Grundfarben und rubinrote Augen. In Kombination mit Albino entsteht der beliebte Toffee Glow.',
                    'visuals' => [
                        'dominant' => 'Normal',
                        'heterozygous' => 'Het Toffee Belly',
                        'recessive' => 'Toffee Belly'
                    ]
                ],
                [
                    'name' => 'Hypomelanistic',
                    'inheritance' => 'recessive',
                    'description' => 'Reduziert schwarze Pigmente und sorgt für weichere Zeichnungen. Häufig mit Anaconda oder Arctic kombiniert, um Ghost-Linien zu erhalten.',
                    'visuals' => [
                        'dominant' => 'Normal',
                        'heterozygous' => 'Het Hypo',
                        'recessive' => 'Hypomelanistic'
                    ]
                ],
                [
                    'name' => 'Sable',
                    'inheritance' => 'recessive',
                    'description' => 'Dunkelt Tiere stark ein und erzeugt kaffeebraune bis schwarze Grundfarben mit reduziertem Muster. In Superform entstehen fast einfarbige Tiere.',
                    'visuals' => [
                        'dominant' => 'Normal',
                        'heterozygous' => 'Het Sable',
                        'recessive' => 'Sable'
                    ]
                ],
                [
                    'name' => 'Leucistic',
                    'inheritance' => 'recessive',
                    'description' => 'Seltene Linie, die nahezu pigmentlose Tiere mit schwarzen Augen hervorbringt. Benötigt sorgfältige Aufzucht, da Jungtiere sensibel reagieren.',
                    'visuals' => [
                        'dominant' => 'Normal',
                        'heterozygous' => 'Het Leucistic',
                        'recessive' => 'Leucistic'
                    ]
                ],
                [
                    'name' => 'Extreme Red Albino',
                    'inheritance' => 'recessive',
                    'description' => 'Line-bred Variante der Albinos mit intensiv roter Grundfarbe und roten Augen. Zeigt besonders kräftige Bauchfärbung.',
                    'visuals' => [
                        'dominant' => 'Normal',
                        'heterozygous' => 'Het Extreme Red Albino',
                        'recessive' => 'Extreme Red Albino'
                    ]
                ],
                [
                    'name' => 'Arctic',
                    'inheritance' => 'co-dominant',
                    'description' => 'Erzeugt weiß-graue Tiere mit kräftigen Sattelflecken. Homozygote Super Arctics besitzen eisige Kontraste und oft einen blauen Schimmer.',
                    'visuals' => [
                        'dominant' => 'Super Arctic',
                        'heterozygous' => 'Arctic',
                        'recessive' => 'Normal'
                    ]
                ],
                [
                    'name' => 'Anaconda',
                    'inheritance' => 'co-dominant',
                    'description' => 'Reduziert die Rückenzeichnung zu wenigen großen Flecken. Die Superform „Superconda“ ist nahezu patternlos und zeigt einfarbige Flanken.',
                    'visuals' => [
                        'dominant' => 'Superconda',
                        'heterozygous' => 'Anaconda',
                        'recessive' => 'Normal'
                    ]
                ],
                [
                    'name' => 'Conda Arctic',
                    'inheritance' => 'co-dominant',
                    'description' => 'Projektname für Tiere, die sowohl Anaconda als auch Arctic tragen. In Superformen entstehen nahezu weiße Patternless-Snows.',
                    'visuals' => [
                        'dominant' => 'Super Arctic Superconda',
                        'heterozygous' => 'Conda Arctic',
                        'recessive' => 'Normal'
                    ]
                ],
                [
                    'name' => 'Coral',
                    'inheritance' => 'dominant',
                    'description' => 'Selektionslinie, die Albino-bedingte Rosa- und Orangetöne intensiviert. Besonders gefragt für farbintensive Extreme-Red-Combos.',
                    'visuals' => [
                        'dominant' => 'Coral (homozygot)',
                        'heterozygous' => 'Coral',
                        'recessive' => 'Normal'
                    ]
                ],
                [
                    'name' => 'Smoke',
                    'inheritance' => 'dominant',
                    'description' => 'Verdunkelt die Grundfarbe zu kalten Grautönen und verstärkt den Kontrast. Lässt sich hervorragend mit Axanthic und Arctic kombinieren.',
                    'visuals' => [
                        'dominant' => 'Smoke (homozygot)',
                        'heterozygous' => 'Smoke',
                        'recessive' => 'Normal'
                    ]
                ]
            ]
        ]
    ];

    foreach ($speciesData as $slug => $data) {
        $speciesId = upsertSpecies($pdo, $slug, $data);
        foreach ($data['genes'] as $gene) {
            upsertGene($pdo, $speciesId, $gene);
        }
    }
}

function upsertSpecies(PDO $pdo, string $slug, array $data): int
{
    $stmt = $pdo->prepare('SELECT id FROM species WHERE slug = ?');
    $stmt->execute([$slug]);
    $id = $stmt->fetchColumn();

    if ($id) {
        $update = $pdo->prepare('UPDATE species SET latin_name = ?, common_name = ?, description = ?, habitat = ?, care_notes = ? WHERE id = ?');
        $update->execute([
            $data['latin_name'],
            $data['common_name'],
            $data['description'],
            $data['habitat'],
            $data['care_notes'],
            $id
        ]);
        return (int)$id;
    }

    $insert = $pdo->prepare('INSERT INTO species (slug, latin_name, common_name, description, habitat, care_notes) VALUES (?, ?, ?, ?, ?, ?)');
    $insert->execute([
        $slug,
        $data['latin_name'],
        $data['common_name'],
        $data['description'],
        $data['habitat'],
        $data['care_notes']
    ]);

    return (int)$pdo->lastInsertId();
}

function upsertGene(PDO $pdo, int $speciesId, array $gene): void
{
    $stmt = $pdo->prepare('SELECT id FROM genes WHERE species_id = ? AND name = ?');
    $stmt->execute([$speciesId, $gene['name']]);
    $id = $stmt->fetchColumn();
    $visualsJson = json_encode($gene['visuals'], JSON_UNESCAPED_UNICODE);

    if ($id) {
        $update = $pdo->prepare('UPDATE genes SET inheritance = ?, description = ?, visuals = ? WHERE id = ?');
        $update->execute([
            $gene['inheritance'],
            $gene['description'],
            $visualsJson,
            $id
        ]);
        return;
    }

    $insert = $pdo->prepare('INSERT INTO genes (species_id, name, inheritance, description, visuals) VALUES (?, ?, ?, ?, ?)');
    $insert->execute([
        $speciesId,
        $gene['name'],
        $gene['inheritance'],
        $gene['description'],
        $visualsJson
    ]);
}

function render(string $view, array $params = [], string $layout = 'layout'): void
{
    global $pdo;
    $viewFile = __DIR__ . '/views/' . $view . '.php';
    if (!file_exists($viewFile)) {
        notFound();
    }

    if (!array_key_exists('pages', $params)) {
        $params['pages'] = fetchMenuPages($pdo);
    }

    if (!array_key_exists('currentUser', $params)) {
        $params['currentUser'] = currentUser($pdo);
    }

    extract($params);
    ob_start();
    include $viewFile;
    $content = ob_get_clean();

    include __DIR__ . '/views/' . $layout . '.php';
}

function renderAdmin(string $view, array $params = []): void
{
    global $pdo;
    if (!array_key_exists('currentUser', $params)) {
        $params['currentUser'] = currentUser($pdo);
    }
    if (!array_key_exists('navItems', $params)) {
        $params['navItems'] = adminNavigation($params['currentUser']);
    }

    render('admin/' . $view, $params, 'admin/layout');
}

function renderAccount(string $view, array $params = []): void
{
    global $pdo;
    if (!array_key_exists('currentUser', $params)) {
        $params['currentUser'] = currentUser($pdo);
    }

    render('account/' . $view, $params, 'account/layout');
}

function fetchMenuPages(PDO $pdo): array
{
    $stmt = $pdo->query('SELECT id, title, slug, parent_id, menu_order, is_visible FROM pages WHERE is_visible = 1 ORDER BY menu_order ASC, title ASC');
    $pages = $stmt->fetchAll();
    return buildPageTree($pages);
}

function fetchPagesFlat(PDO $pdo): array
{
    $stmt = $pdo->query('SELECT * FROM pages ORDER BY menu_order ASC, title ASC');
    return $stmt->fetchAll();
}

function buildPageTree(array $pages): array
{
    $indexed = [];
    foreach ($pages as $page) {
        $page['children'] = [];
        $indexed[$page['id']] = $page;
    }

    foreach ($indexed as $id => &$page) {
        $parentId = $page['parent_id'] ?? null;
        if ($parentId && isset($indexed[$parentId])) {
            $indexed[$parentId]['children'][] = &$page;
        }
    }
    unset($page);

    $tree = [];
    foreach ($indexed as $page) {
        $parentId = $page['parent_id'] ?? null;
        if (!$parentId || !isset($indexed[$parentId])) {
            $tree[] = $page;
        }
    }

    return $tree;
}

function flattenPageTree(array $tree, int $depth = 0, array &$result = []): array
{
    foreach ($tree as $node) {
        $result[] = $node + ['depth' => $depth];
        if (!empty($node['children'])) {
            flattenPageTree($node['children'], $depth + 1, $result);
        }
    }

    return $result;
}

function resolveParentId(PDO $pdo, ?int $pageId, ?int $parentId): ?int
{
    if (!$parentId) {
        return null;
    }

    if ($pageId && $parentId === $pageId) {
        return null;
    }

    $current = $parentId;
    while ($current) {
        $stmt = $pdo->prepare('SELECT id, parent_id FROM pages WHERE id = ?');
        $stmt->execute([$current]);
        $row = $stmt->fetch();
        if (!$row) {
            return null;
        }
        if ($pageId && (int)$row['id'] === $pageId) {
            return null;
        }
        $current = isset($row['parent_id']) && $row['parent_id'] ? (int)$row['parent_id'] : 0;
    }

    return (int)$parentId;
}

function nextMenuOrder(PDO $pdo): int
{
    $max = (int)$pdo->query('SELECT MAX(menu_order) FROM pages')->fetchColumn();
    return $max + 10;
}

function currentUser(PDO $pdo): ?array
{
    if (!isset($_SESSION['user_id'])) {
        return null;
    }

    $stmt = $pdo->prepare('SELECT * FROM users WHERE id = ?');
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch();

    if ($user) {
        $permissions = $user['permissions'] ?? null;
        $user['permissions'] = $permissions ? json_decode($permissions, true) : [];
        if (!is_array($user['permissions'])) {
            $user['permissions'] = [];
        }
    } else {
        $user = null;
    }
    return $user;
}

function permissionLabels(): array
{
    return [
        'posts' => 'Beiträge verwalten',
        'pages' => 'Seiten & Menü',
        'gallery' => 'Galerie',
        'animals' => 'Tiere',
        'genetics' => 'Genetikdaten',
        'users' => 'Benutzerverwaltung'
    ];
}

function normalizePermissions($values): array
{
    $allowed = array_keys(permissionLabels());
    $normalized = [];
    if (is_array($values)) {
        foreach ($values as $value) {
            if (in_array($value, $allowed, true)) {
                $normalized[$value] = true;
            }
        }
    }
    return $normalized;
}

function userHasPermission(?array $user, string $permission): bool
{
    if (!$user) {
        return false;
    }

    if (($user['role'] ?? '') === 'admin') {
        return true;
    }

    $permissions = $user['permissions'] ?? [];
    return !empty($permissions[$permission]);
}

function adminNavigation(?array $user): array
{
    $items = [
        ['label' => 'Dashboard', 'route' => 'admin', 'permission' => null],
        ['label' => 'Beiträge', 'route' => 'admin/posts', 'permission' => 'posts'],
        ['label' => 'Seiten & Menü', 'route' => 'admin/pages', 'permission' => 'pages'],
        ['label' => 'Galerie', 'route' => 'admin/gallery', 'permission' => 'gallery'],
        ['label' => 'Tiere', 'route' => 'admin/animals', 'permission' => 'animals'],
        ['label' => 'Genetik', 'route' => 'admin/genetics', 'permission' => 'genetics'],
        ['label' => 'Benutzer', 'route' => 'admin/users', 'permission' => 'users']
    ];

    $available = [];
    foreach ($items as $item) {
        if ($item['permission'] === null) {
            if ($user) {
                $available[] = $item;
            }
            continue;
        }

        if (userHasPermission($user, $item['permission'])) {
            $available[] = $item;
        }
    }

    return $available;
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
    return fetchPagesFlat($pdo);
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

function requireLogin(PDO $pdo): array
{
    $user = currentUser($pdo);
    if (!$user) {
        header('Location: ' . url('login'));
        exit;
    }

    return $user;
}

function requireAdmin(PDO $pdo, ?string $permission = null): array
{
    $user = requireLogin($pdo);

    if ($permission !== null && !userHasPermission($user, $permission)) {
        setFlash('Für diesen Bereich fehlen die Berechtigungen.', 'error');
        header('Location: ' . url('admin'));
        exit;
    }

    return $user;
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
            $redirectRoute = 'admin';
            $permissions = $user['permissions'] ?? [];
            if (!is_array($permissions)) {
                $permissions = [];
            }
            if (($user['role'] ?? '') !== 'admin' && empty($permissions)) {
                $redirectRoute = 'account/animals';
            }
            header('Location: ' . url($redirectRoute));
            exit;
        }

        setFlash('Ungültige Zugangsdaten.', 'error');
    }

    render('login');
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
        $now = date(DATE_ATOM);

        if ($action === 'delete' && $id) {
            $pdo->prepare('UPDATE pages SET parent_id = NULL WHERE parent_id = ?')->execute([$id]);
            $stmt = $pdo->prepare('DELETE FROM pages WHERE id = ?');
            $stmt->execute([$id]);
            setFlash('Seite wurde gelöscht.', 'success');
            header('Location: ' . url('admin/pages'));
            exit;
        }

        if ($action === 'update-menu' && $id) {
            $menuOrder = (int)($_POST['menu_order'] ?? 0);
            $isVisible = isset($_POST['is_visible']) ? 1 : 0;
            $parentId = resolveParentId($pdo, $id, (int)($_POST['parent_id'] ?? 0));
            if ($menuOrder === 0) {
                $menuOrder = nextMenuOrder($pdo);
            }
            $stmt = $pdo->prepare('UPDATE pages SET parent_id = ?, menu_order = ?, is_visible = ?, updated_at = ? WHERE id = ?');
            $stmt->execute([$parentId, $menuOrder, $isVisible, $now, $id]);
            setFlash('Menüeinstellungen aktualisiert.', 'success');
            header('Location: ' . url('admin/pages'));
            exit;
        }

        if ($action === 'create' || $action === 'update') {
            $title = trim($_POST['title'] ?? '');
            $slug = slugify($_POST['slug'] ?? $title);
            $content = trim($_POST['content'] ?? '');
            $menuOrder = isset($_POST['menu_order']) ? (int)$_POST['menu_order'] : nextMenuOrder($pdo);
            if ($menuOrder === 0) {
                $menuOrder = nextMenuOrder($pdo);
            }
            $isVisible = isset($_POST['is_visible']) ? 1 : 0;
            $parentId = resolveParentId($pdo, $id ?: null, (int)($_POST['parent_id'] ?? 0));

            if ($title === '' || $content === '') {
                setFlash('Titel und Inhalt sind erforderlich.', 'error');
                header('Location: ' . url('admin/pages'));
                exit;
            }

            if ($id) {
                $stmt = $pdo->prepare('UPDATE pages SET title = ?, slug = ?, content = ?, parent_id = ?, menu_order = ?, is_visible = ?, updated_at = ? WHERE id = ?');
                $stmt->execute([$title, $slug, $content, $parentId, $menuOrder, $isVisible, $now, $id]);
                setFlash('Seite aktualisiert.', 'success');
            } else {
                $stmt = $pdo->prepare('INSERT INTO pages (title, slug, content, parent_id, menu_order, is_visible, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?)');
                $stmt->execute([$title, $slug, $content, $parentId, $menuOrder, $isVisible, $now]);
                setFlash('Seite erstellt.', 'success');
            }

            header('Location: ' . url('admin/pages'));
            exit;
        }

        setFlash('Unbekannte Aktion.', 'error');
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

    $pagesFlat = fetchPagesFlat($pdo);
    $pageTree = buildPageTree($pagesFlat);
    $parentChoices = flattenPageTree($pageTree);

    renderAdmin('pages/index', [
        'pageTree' => $pageTree,
        'parentOptions' => array_values(array_filter($parentChoices, function ($page) use ($editId) {
            return !$editId || (int)$page['id'] !== $editId;
        })),
        'editPage' => $editPage,
        'defaultMenuOrder' => nextMenuOrder($pdo)
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

function handleAnimals(PDO $pdo, string $method): void
{
    $currentUser = currentUser($pdo);

    if ($method === 'POST') {
        $action = $_POST['action'] ?? '';

        if ($action === 'delete-animal') {
            $id = (int)($_POST['id'] ?? 0);
            if ($id) {
                deleteAnimal($pdo, $id);
                setFlash('Tier entfernt.', 'success');
            }
            header('Location: ' . url('admin/animals'));
            exit;
        }

        if ($action === 'save-animal') {
            $id = (int)($_POST['id'] ?? 0);
            $speciesId = (int)($_POST['species_id'] ?? 0);
            $name = trim($_POST['name'] ?? '');
            $age = trim($_POST['age'] ?? '');
            $origin = trim($_POST['origin'] ?? '');
            $geneticsNotes = trim($_POST['genetics_notes'] ?? '');
            $specialNotes = trim($_POST['special_notes'] ?? '');
            $isShowcased = isset($_POST['is_showcased']) ? 1 : 0;
            $isPrivate = isset($_POST['is_private']) ? 1 : 0;
            $ownerId = (int)($_POST['owner_id'] ?? 0);
            if ($ownerId > 0 && !findUser($pdo, $ownerId)) {
                $ownerId = 0;
            }
            if ($ownerId <= 0 && $id) {
                $existing = findAnimal($pdo, $id);
                if ($existing && !empty($existing['owner_id'])) {
                    $ownerId = (int)$existing['owner_id'];
                }
            }
            if ($ownerId <= 0 && $currentUser) {
                $ownerId = (int)$currentUser['id'];
            }
            if ($isPrivate) {
                $isShowcased = 0;
            }
            $genotypeInput = $_POST['genotypes'] ?? [];
            $removeImages = $_POST['remove_images'] ?? [];

            if ($speciesId === 0 || $name === '') {
                setFlash('Bitte einen Namen und eine Art für das Tier wählen.', 'error');
                header('Location: ' . url('admin/animals', ['id' => $id ?: null]));
                exit;
            }

            $animalId = saveAnimal($pdo, [
                'id' => $id,
                'species_id' => $speciesId,
                'name' => $name,
                'age' => $age,
                'origin' => $origin,
                'genetics_notes' => $geneticsNotes,
                'special_notes' => $specialNotes,
                'is_showcased' => $isShowcased,
                'is_private' => $isPrivate,
                'owner_id' => $ownerId
            ]);

            $genes = fetchGenesForSpecies($pdo, $speciesId);
            $geneMap = [];
            foreach ($genes as $gene) {
                $geneMap[$gene['id']] = $gene;
            }

            $pdo->prepare('DELETE FROM animal_genotypes WHERE animal_id = ?')->execute([$animalId]);
            foreach ($genotypeInput as $geneId => $value) {
                $geneId = (int)$geneId;
                if (!isset($geneMap[$geneId])) {
                    continue;
                }
                $state = in_array($value, ['dominant', 'heterozygous', 'recessive'], true) ? $value : null;
                if ($state === null) {
                    continue;
                }
                $defaultState = defaultGenotypeForInheritance($geneMap[$geneId]['inheritance']);
                if ($state === $defaultState) {
                    continue;
                }
                $stmt = $pdo->prepare('INSERT INTO animal_genotypes (animal_id, gene_id, genotype) VALUES (?, ?, ?)');
                $stmt->execute([$animalId, $geneId, $state]);
            }

            if (!empty($removeImages)) {
                foreach ($removeImages as $imageId) {
                    $imageId = (int)$imageId;
                    deleteAnimalImage($pdo, $animalId, $imageId);
                }
            }

            if (!empty($_FILES['images']) && is_array($_FILES['images']['name'])) {
                $fileCount = count($_FILES['images']['name']);
                for ($i = 0; $i < $fileCount; $i++) {
                    $file = [
                        'name' => $_FILES['images']['name'][$i],
                        'type' => $_FILES['images']['type'][$i],
                        'tmp_name' => $_FILES['images']['tmp_name'][$i],
                        'error' => $_FILES['images']['error'][$i],
                        'size' => $_FILES['images']['size'][$i]
                    ];
                    if ($file['error'] !== UPLOAD_ERR_OK) {
                        continue;
                    }
                    $path = handleUpload($file);
                    if ($path !== '') {
                        $stmt = $pdo->prepare('INSERT INTO animal_images (animal_id, image_path, created_at) VALUES (?, ?, ?)');
                        $stmt->execute([$animalId, $path, date(DATE_ATOM)]);
                    }
                }
            }

            setFlash($id ? 'Tier aktualisiert.' : 'Tier angelegt.', 'success');
            header('Location: ' . url('admin/animals', ['id' => $animalId]));
            exit;
        }
    }

    $animals = fetchAnimals($pdo);
    $species = fetchSpecies($pdo);
    $genesBySpecies = [];
    foreach ($species as $sp) {
        $genesBySpecies[$sp['id']] = fetchGenesForSpecies($pdo, $sp['id']);
    }

    $editId = isset($_GET['id']) ? (int)$_GET['id'] : null;
    $editAnimal = null;
    $editGenotypes = [];
    $editImages = [];
    if ($editId) {
        $editAnimal = findAnimal($pdo, $editId);
        if ($editAnimal) {
            $editGenotypes = fetchAnimalGenotypes($pdo, $editId);
            $editImages = fetchAnimalImages($pdo, $editId);
        }
    }

    renderAdmin('animals/index', [
        'animals' => $animals,
        'species' => $species,
        'genesBySpecies' => $genesBySpecies,
        'editAnimal' => $editAnimal,
        'editGenotypes' => $editGenotypes,
        'editImages' => $editImages,
        'users' => fetchUsers($pdo)
    ]);
}

function handleAccountAnimals(PDO $pdo, array $user): void
{
    $animals = fetchAnimalsForOwner($pdo, (int)$user['id']);
    $genesBySpecies = [];

    foreach ($animals as &$animal) {
        $speciesId = (int)$animal['species_id'];
        if (!isset($genesBySpecies[$speciesId])) {
            $genesBySpecies[$speciesId] = fetchGenesForSpecies($pdo, $speciesId);
        }
        $genes = $genesBySpecies[$speciesId];
        $animal['genotype_map'] = buildAnimalGenotypeMap($genes, $animal['genotypes'] ?? []);
        $animal['primary_image'] = $animal['images'][0]['image_path'] ?? null;
        $animal['gene_summary'] = summarizeGeneStates($genes, $animal['genotype_map']);
    }

    renderAccount('animals', [
        'animals' => $animals,
        'genesBySpecies' => $genesBySpecies,
        'currentUser' => $user
    ]);
}

function handleUsers(PDO $pdo, string $method): void
{
    $currentUser = currentUser($pdo);

    if ($method === 'POST') {
        $action = $_POST['action'] ?? 'save';
        $id = (int)($_POST['id'] ?? 0);

        if ($action === 'delete' && $id) {
            if ($currentUser && (int)$currentUser['id'] === $id) {
                setFlash('Du kannst dich nicht selbst löschen.', 'error');
                header('Location: ' . url('admin/users'));
                exit;
            }

            if (countAdmins($pdo, $id) === 0) {
                setFlash('Der letzte Administrator kann nicht gelöscht werden.', 'error');
                header('Location: ' . url('admin/users'));
                exit;
            }

            $stmt = $pdo->prepare('DELETE FROM users WHERE id = ?');
            $stmt->execute([$id]);
            setFlash('Benutzer entfernt.', 'success');
            header('Location: ' . url('admin/users'));
            exit;
        }

        if ($action === 'save') {
            $username = trim($_POST['username'] ?? '');
            $role = $_POST['role'] === 'admin' ? 'admin' : 'editor';
            $permissions = $role === 'admin' ? [] : normalizePermissions($_POST['permissions'] ?? []);
            $password = $_POST['password'] ?? '';
            $confirm = $_POST['password_confirm'] ?? '';

            if ($username === '') {
                setFlash('Der Benutzername darf nicht leer sein.', 'error');
                header('Location: ' . url('admin/users', ['id' => $id ?: null]));
                exit;
            }

            if ($id === 0 && $password === '') {
                setFlash('Für neue Benutzer muss ein Passwort gesetzt werden.', 'error');
                header('Location: ' . url('admin/users'));
                exit;
            }

            if ($password !== '' && $password !== $confirm) {
                setFlash('Die Passwörter stimmen nicht überein.', 'error');
                header('Location: ' . url('admin/users', ['id' => $id ?: null]));
                exit;
            }

            if ($id && $role !== 'admin' && countAdmins($pdo, $id) === 0) {
                setFlash('Der letzte Administrator kann nicht herabgestuft werden.', 'error');
                header('Location: ' . url('admin/users', ['id' => $id]));
                exit;
            }

            $stmt = $pdo->prepare('SELECT id FROM users WHERE username = ? AND id != ?');
            $stmt->execute([$username, $id]);
            if ($stmt->fetch()) {
                setFlash('Benutzername ist bereits vergeben.', 'error');
                header('Location: ' . url('admin/users', ['id' => $id ?: null]));
                exit;
            }

            $permissionsJson = $role === 'admin' ? null : json_encode($permissions, JSON_UNESCAPED_UNICODE);

            if ($id) {
                $params = [$username, $role, $permissionsJson, $id];
                $pdo->prepare('UPDATE users SET username = ?, role = ?, permissions = ? WHERE id = ?')->execute($params);
                if ($password !== '') {
                    $pdo->prepare('UPDATE users SET password_hash = ? WHERE id = ?')->execute([password_hash($password, PASSWORD_DEFAULT), $id]);
                }
                if ($currentUser && (int)$currentUser['id'] === $id) {
                    $_SESSION['username'] = $username;
                }
                setFlash('Benutzer aktualisiert.', 'success');
                $targetId = $id;
            } else {
                $stmt = $pdo->prepare('INSERT INTO users (username, password_hash, role, permissions, created_at) VALUES (?, ?, ?, ?, ?)');
                $stmt->execute([
                    $username,
                    password_hash($password, PASSWORD_DEFAULT),
                    $role,
                    $permissionsJson,
                    date(DATE_ATOM)
                ]);
                $targetId = (int)$pdo->lastInsertId();
                setFlash('Benutzer angelegt.', 'success');
            }

            header('Location: ' . url('admin/users', ['id' => $targetId]));
            exit;
        }

        setFlash('Unbekannte Aktion.', 'error');
        header('Location: ' . url('admin/users'));
        exit;
    }

    $editId = isset($_GET['id']) ? (int)$_GET['id'] : null;
    $editUser = $editId ? findUser($pdo, $editId) : null;

    renderAdmin('users/index', [
        'users' => fetchUsers($pdo),
        'editUser' => $editUser,
        'permissionLabels' => permissionLabels(),
        'activeUser' => $currentUser
    ]);
}

function fetchUsers(PDO $pdo): array
{
    $stmt = $pdo->query('SELECT id, username, role, permissions, created_at FROM users ORDER BY username ASC');
    $users = $stmt->fetchAll();
    foreach ($users as &$user) {
        $perms = $user['permissions'] ?? null;
        $user['permissions'] = $perms ? json_decode($perms, true) : [];
        if (!is_array($user['permissions'])) {
            $user['permissions'] = [];
        }
    }
    return $users;
}

function findUser(PDO $pdo, int $id): ?array
{
    $stmt = $pdo->prepare('SELECT id, username, role, permissions, created_at FROM users WHERE id = ?');
    $stmt->execute([$id]);
    $user = $stmt->fetch();
    if (!$user) {
        return null;
    }
    $perms = $user['permissions'] ?? null;
    $user['permissions'] = $perms ? json_decode($perms, true) : [];
    if (!is_array($user['permissions'])) {
        $user['permissions'] = [];
    }
    return $user;
}

function countAdmins(PDO $pdo, ?int $excludeId = null): int
{
    if ($excludeId) {
        $stmt = $pdo->prepare('SELECT COUNT(*) FROM users WHERE role = "admin" AND id != ?');
        $stmt->execute([$excludeId]);
        return (int)$stmt->fetchColumn();
    }

    return (int)$pdo->query('SELECT COUNT(*) FROM users WHERE role = "admin"')->fetchColumn();
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

function fetchAnimals(PDO $pdo): array
{
    $stmt = $pdo->query('SELECT a.*, s.common_name, s.latin_name, u.username AS owner_username FROM animals a JOIN species s ON s.id = a.species_id LEFT JOIN users u ON u.id = a.owner_id ORDER BY datetime(a.updated_at) DESC');
    $animals = $stmt->fetchAll();
    foreach ($animals as &$animal) {
        $animal['genotypes'] = fetchAnimalGenotypes($pdo, $animal['id']);
        $animal['images'] = fetchAnimalImages($pdo, $animal['id']);
    }
    return $animals;
}

function fetchShowcasedAnimals(PDO $pdo): array
{
    $stmt = $pdo->query('SELECT a.*, s.common_name, s.latin_name, u.username AS owner_username FROM animals a JOIN species s ON s.id = a.species_id LEFT JOIN users u ON u.id = a.owner_id WHERE a.is_showcased = 1 AND a.is_private = 0 ORDER BY datetime(a.updated_at) DESC');
    $animals = $stmt->fetchAll();
    foreach ($animals as &$animal) {
        $animal['genotypes'] = fetchAnimalGenotypes($pdo, $animal['id']);
        $animal['images'] = fetchAnimalImages($pdo, $animal['id']);
    }
    return $animals;
}

function fetchAnimalsBySpecies(PDO $pdo, int $speciesId, ?int $userId = null): array
{
    $sql = 'SELECT a.* FROM animals a WHERE a.species_id = ?';
    $params = [$speciesId];
    if ($userId === null) {
        $sql .= ' AND a.is_private = 0';
    } else {
        $sql .= ' AND (a.is_private = 0 OR a.owner_id = ?)';
        $params[] = $userId;
    }
    $sql .= ' ORDER BY a.name ASC';
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $animals = $stmt->fetchAll();
    foreach ($animals as &$animal) {
        $animal['genotypes'] = fetchAnimalGenotypes($pdo, $animal['id']);
        $animal['images'] = fetchAnimalImages($pdo, $animal['id']);
    }
    return $animals;
}

function fetchAnimalsForOwner(PDO $pdo, int $userId): array
{
    $stmt = $pdo->prepare('SELECT a.*, s.common_name, s.latin_name FROM animals a JOIN species s ON s.id = a.species_id WHERE a.owner_id = ? ORDER BY datetime(a.updated_at) DESC');
    $stmt->execute([$userId]);
    $animals = $stmt->fetchAll();
    foreach ($animals as &$animal) {
        $animal['genotypes'] = fetchAnimalGenotypes($pdo, $animal['id']);
        $animal['images'] = fetchAnimalImages($pdo, $animal['id']);
    }
    return $animals;
}

function findAnimal(PDO $pdo, int $id): ?array
{
    $stmt = $pdo->prepare('SELECT * FROM animals WHERE id = ?');
    $stmt->execute([$id]);
    $animal = $stmt->fetch();
    return $animal ?: null;
}

function saveAnimal(PDO $pdo, array $data): int
{
    $now = date(DATE_ATOM);
    $ownerId = isset($data['owner_id']) && (int)$data['owner_id'] > 0 ? (int)$data['owner_id'] : null;
    $isPrivate = (int)!empty($data['is_private']);
    $isShowcased = (int)!empty($data['is_showcased']);
    if (!empty($data['id'])) {
        $stmt = $pdo->prepare('UPDATE animals SET species_id = ?, owner_id = ?, name = ?, slug = ?, age = ?, origin = ?, genetics_notes = ?, special_notes = ?, is_showcased = ?, is_private = ?, updated_at = ? WHERE id = ?');
        $slug = $data['slug'] ?? slugify($data['name']);
        $stmt->execute([
            $data['species_id'],
            $ownerId,
            $data['name'],
            $slug,
            $data['age'],
            $data['origin'],
            $data['genetics_notes'],
            $data['special_notes'],
            $isShowcased,
            $isPrivate,
            $now,
            $data['id']
        ]);
        return (int)$data['id'];
    }

    $stmt = $pdo->prepare('INSERT INTO animals (species_id, owner_id, name, slug, age, origin, genetics_notes, special_notes, is_showcased, is_private, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
    $slug = $data['slug'] ?? slugify($data['name']);
    $stmt->execute([
        $data['species_id'],
        $ownerId,
        $data['name'],
        $slug,
        $data['age'],
        $data['origin'],
        $data['genetics_notes'],
        $data['special_notes'],
        $isShowcased,
        $isPrivate,
        $now,
        $now
    ]);

    return (int)$pdo->lastInsertId();
}

function deleteAnimal(PDO $pdo, int $id): void
{
    $images = fetchAnimalImages($pdo, $id);
    foreach ($images as $image) {
        $relative = ltrim(parse_url($image['image_path'], PHP_URL_PATH), '/');
        $path = __DIR__ . '/' . $relative;
        if (is_file($path)) {
            @unlink($path);
        }
    }
    $pdo->prepare('DELETE FROM animals WHERE id = ?')->execute([$id]);
}

function fetchAnimalGenotypes(PDO $pdo, int $animalId): array
{
    $stmt = $pdo->prepare('SELECT gene_id, genotype FROM animal_genotypes WHERE animal_id = ?');
    $stmt->execute([$animalId]);
    $rows = $stmt->fetchAll();
    $map = [];
    foreach ($rows as $row) {
        $map[(int)$row['gene_id']] = $row['genotype'];
    }
    return $map;
}

function fetchAnimalImages(PDO $pdo, int $animalId): array
{
    $stmt = $pdo->prepare('SELECT id, image_path FROM animal_images WHERE animal_id = ? ORDER BY created_at ASC');
    $stmt->execute([$animalId]);
    return $stmt->fetchAll();
}

function deleteAnimalImage(PDO $pdo, int $animalId, int $imageId): void
{
    $stmt = $pdo->prepare('SELECT image_path FROM animal_images WHERE id = ? AND animal_id = ?');
    $stmt->execute([$imageId, $animalId]);
    $image = $stmt->fetch();
    if ($image) {
        $pdo->prepare('DELETE FROM animal_images WHERE id = ?')->execute([$imageId]);
        $relative = ltrim(parse_url($image['image_path'], PHP_URL_PATH), '/');
        $path = __DIR__ . '/' . $relative;
        if (is_file($path)) {
            @unlink($path);
        }
    }
}

function defaultGenotypeForInheritance(string $inheritance): string
{
    return match ($inheritance) {
        'recessive' => 'dominant',
        default => 'recessive'
    };
}

function defaultSelectionsForGenes(array $genes): array
{
    $defaults = [];
    foreach ($genes as $gene) {
        $defaults[$gene['id']] = defaultGenotypeForInheritance($gene['inheritance']);
    }
    return $defaults;
}

function parseParentSelections($values, array $genes): array
{
    if (!is_array($values)) {
        $values = $values === null ? [] : [$values];
    }
    $selections = defaultSelectionsForGenes($genes);
    $geneIds = array_column($genes, 'id');
    $validGeneIds = array_flip($geneIds);

    foreach ($values as $value) {
        if (!is_string($value) || strpos($value, ':') === false) {
            continue;
        }
        [$geneId, $state] = explode(':', $value, 2);
        $geneId = (int)$geneId;
        if (!isset($validGeneIds[$geneId])) {
            continue;
        }
        if (!in_array($state, ['dominant', 'heterozygous', 'recessive'], true)) {
            continue;
        }
        $selections[$geneId] = $state;
    }

    return $selections;
}

function selectionValuesFromMap(array $map, array $genes): array
{
    $values = [];
    $defaults = defaultSelectionsForGenes($genes);
    foreach ($map as $geneId => $state) {
        $default = $defaults[$geneId] ?? null;
        if ($default === null) {
            continue;
        }
        if ($state === $default) {
            continue;
        }
        $values[] = $geneId . ':' . $state;
    }
    return $values;
}

function buildAnimalGenotypeMap(array $genes, array $stored): array
{
    $map = defaultSelectionsForGenes($genes);
    foreach ($stored as $geneId => $state) {
        if (isset($map[$geneId]) && in_array($state, ['dominant', 'heterozygous', 'recessive'], true)) {
            $map[$geneId] = $state;
        }
    }
    return $map;
}

function handleGeneticsCalculator(PDO $pdo, string $method): void
{
    $currentUser = currentUser($pdo);
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
    $parentAValues = [];
    $parentBValues = [];
    $parentAnimalSelection = ['a' => null, 'b' => null];
    $availableAnimals = [];

    if ($selectedSpeciesId) {
        foreach ($speciesList as $sp) {
            if ((int)$sp['id'] === $selectedSpeciesId) {
                $selectedSpecies = $sp;
                break;
            }
        }
        if ($selectedSpecies) {
            $genes = fetchGenesForSpecies($pdo, $selectedSpeciesId);
            $availableAnimals = fetchAnimalsBySpecies($pdo, $selectedSpeciesId, $currentUser['id'] ?? null);
        }
    }

    if ($method === 'POST' && $selectedSpecies) {
        $parentAAnimalId = (int)($_POST['parent_a_animal'] ?? 0);
        $parentBAnimalId = (int)($_POST['parent_b_animal'] ?? 0);
        $parentAnimalSelection = ['a' => $parentAAnimalId ?: null, 'b' => $parentBAnimalId ?: null];

        $animalsById = [];
        foreach ($availableAnimals as $animal) {
            $animalsById[$animal['id']] = $animal;
        }

        if ($parentAAnimalId && isset($animalsById[$parentAAnimalId])) {
            $parentASelections = buildAnimalGenotypeMap($genes, $animalsById[$parentAAnimalId]['genotypes'] ?? []);
        } else {
            $parentASelections = parseParentSelections($_POST['parent_a_genes'] ?? [], $genes);
        }

        if ($parentBAnimalId && isset($animalsById[$parentBAnimalId])) {
            $parentBSelections = buildAnimalGenotypeMap($genes, $animalsById[$parentBAnimalId]['genotypes'] ?? []);
        } else {
            $parentBSelections = parseParentSelections($_POST['parent_b_genes'] ?? [], $genes);
        }

        $parentAValues = selectionValuesFromMap($parentASelections, $genes);
        $parentBValues = selectionValuesFromMap($parentBSelections, $genes);
        $calculation = computeGenetics($genes, $parentASelections, $parentBSelections);
    } elseif ($selectedSpecies) {
        $parentASelections = defaultSelectionsForGenes($genes);
        $parentBSelections = defaultSelectionsForGenes($genes);
    }

    render('genetics/calculator', [
        'speciesList' => $speciesList,
        'selectedSpecies' => $selectedSpecies,
        'genes' => $genes,
        'calculation' => $calculation,
        'parentA' => $parentASelections,
        'parentB' => $parentBSelections,
        'parentAValues' => $parentAValues,
        'parentBValues' => $parentBValues,
        'parentAnimalSelection' => $parentAnimalSelection,
        'animals' => $availableAnimals
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
        $probabilities = [];
        foreach ($punnett as $genotype => $count) {
            $probabilities[$genotype] = $count / $total;
        }
        $variants = [];
        foreach ($punnett as $genotype => $count) {
            $phenotype = matchPhenotype($inheritance, $genotype, $visuals);
            $fraction = $count / $total;
            $variants[] = [
                'genotype' => $genotype,
                'phenotype' => $phenotype,
                'probability' => round($fraction * 100, 2),
                'fraction' => $fraction,
                'descriptor' => descriptorForVariant($gene, $genotype, $phenotype, $probabilities)
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
                if (!empty($variant['descriptor'])) {
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
        $descriptors = normalizeDescriptors($combo['descriptors']);
        $key = descriptorsKey($descriptors);
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

function normalizeDescriptors(array $descriptors): array
{
    $filtered = array_values(array_filter($descriptors, function ($descriptor) {
        return !empty($descriptor);
    }));

    usort($filtered, 'compareDescriptors');

    return $filtered;
}

function compareDescriptors(array $a, array $b): int
{
    $weights = [
        'visual' => 0,
        'het' => 1,
        'possible_het' => 2,
        'normal' => 3,
        'text' => 4
    ];

    $typeA = $a['type'] ?? 'text';
    $typeB = $b['type'] ?? 'text';
    $weightA = $weights[$typeA] ?? 99;
    $weightB = $weights[$typeB] ?? 99;

    if ($weightA === $weightB) {
        $geneCompare = strnatcasecmp($a['gene'] ?? '', $b['gene'] ?? '');
        if ($geneCompare !== 0) {
            return $geneCompare;
        }

        $labelCompare = strnatcasecmp($a['label'] ?? '', $b['label'] ?? '');
        if ($labelCompare !== 0) {
            return $labelCompare;
        }

        $chanceA = $a['chance'] ?? 0;
        $chanceB = $b['chance'] ?? 0;

        return $chanceA <=> $chanceB;
    }

    return $weightA <=> $weightB;
}

function descriptorsKey(array $descriptors): string
{
    $normalized = array_map(function ($descriptor) {
        $base = [
            'type' => $descriptor['type'] ?? 'text',
            'gene' => $descriptor['gene'] ?? '',
            'label' => $descriptor['label'] ?? ''
        ];
        if (isset($descriptor['chance'])) {
            $base['chance'] = round($descriptor['chance'], 4);
        }

        return $base;
    }, $descriptors);

    return md5(json_encode($normalized));
}

function formatDescriptorLabel(array $descriptors): string
{
    if (empty($descriptors)) {
        return 'Normal';
    }

    $visuals = [];
    $hets = [];
    $possibles = [];

    foreach ($descriptors as $descriptor) {
        $type = $descriptor['type'] ?? 'text';
        $label = trim($descriptor['label'] ?? '');

        switch ($type) {
            case 'visual':
                $visuals[] = $label;
                break;
            case 'het':
                $hets[] = $label !== '' ? $label : ('Het ' . ($descriptor['gene'] ?? ''));
                break;
            case 'possible_het':
                $possibles[] = formatPossibleHetLabel($descriptor);
                break;
            case 'text':
            case 'normal':
                if ($label !== '') {
                    $visuals[] = $label;
                }
                break;
        }
    }

    $parts = array_filter(array_merge($visuals, $hets, $possibles));

    $label = trim(implode(' ', $parts));

    return $label !== '' ? $label : 'Normal';
}

function formatPossibleHetLabel(array $descriptor): string
{
    $chance = max(min($descriptor['chance'] ?? 0, 1), 0);
    $percent = $chance * 100;
    $precision = $percent >= 10 ? 1 : 2;
    $formatted = rtrim(rtrim(number_format($percent, $precision), '0'), '.');
    if ($formatted === '') {
        $formatted = '0';
    }

    $gene = $descriptor['gene'] ?? '';
    if ($gene === '') {
        $gene = trim(preg_replace('/^Het\s+/i', '', $descriptor['label'] ?? ''));
    }

    return $formatted . '% pos het ' . $gene;
}

function descriptorForVariant(array $gene, string $genotype, string $phenotype, array $probabilities): ?array
{
    $inheritance = $gene['inheritance'];
    $name = $gene['name'];
    $visuals = $gene['visuals'] ?? [];
    $phenotype = trim($phenotype);

    if ($inheritance === 'recessive') {
        if ($genotype === 'aa') {
            $label = $phenotype !== '' ? $phenotype : $name;
            return [
                'type' => 'visual',
                'gene' => $name,
                'label' => $label
            ];
        }

        if ($genotype === 'Aa') {
            $hetLabel = $visuals['heterozygous'] ?? ('Het ' . $name);
            $probAA = $probabilities['AA'] ?? 0;
            $probAa = $probabilities['Aa'] ?? 0;
            $nonVisual = $probAA + $probAa;

            if ($probAA <= 0 || $nonVisual <= 0) {
                return [
                    'type' => 'het',
                    'gene' => $name,
                    'label' => $hetLabel
                ];
            }

            $chance = $probAa / $nonVisual;

            if ($chance >= 0.999) {
                return [
                    'type' => 'het',
                    'gene' => $name,
                    'label' => $hetLabel
                ];
            }

            return [
                'type' => 'possible_het',
                'gene' => $name,
                'label' => $hetLabel,
                'chance' => $chance
            ];
        }

        return null;
    }

    if ($inheritance === 'co-dominant') {
        if ($genotype === 'AA') {
            $label = $phenotype !== '' ? $phenotype : ($visuals['dominant'] ?? ('Super ' . $name));
            return [
                'type' => 'visual',
                'gene' => $name,
                'label' => $label
            ];
        }

        if ($genotype === 'Aa') {
            $label = $phenotype !== '' ? $phenotype : ($visuals['heterozygous'] ?? $name);
            return [
                'type' => 'visual',
                'gene' => $name,
                'label' => $label
            ];
        }

        return null;
    }

    if ($genotype === 'aa') {
        return null;
    }

    $labelKey = $genotype === 'AA' ? 'dominant' : 'heterozygous';
    $label = $visuals[$labelKey] ?? $phenotype;
    if ($label === '') {
        $label = $gene['name'];
    }

    return [
        'type' => 'visual',
        'gene' => $name,
        'label' => $label
    ];
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

function geneSelectionOptions(array $gene): array
{
    $options = genotypeOptions($gene['inheritance']);
    $choices = [];
    foreach ($options as $key => $label) {
        $choices[$key] = $gene['name'] . ' – ' . $label;
    }
    return $choices;
}

function describeGeneState(array $gene, string $state): string
{
    $inheritance = $gene['inheritance'];
    $name = $gene['name'];
    return match ($inheritance) {
        'recessive' => match ($state) {
            'recessive' => $name,
            'heterozygous' => 'het ' . $name,
            default => 'normal ' . $name
        },
        'co-dominant' => match ($state) {
            'dominant' => 'Super ' . $name,
            'heterozygous' => $name,
            default => 'normal ' . $name
        },
        default => match ($state) {
            'dominant' => $name,
            'heterozygous' => $name,
            default => 'normal ' . $name
        }
    };
}

function summarizeGeneStates(array $genes, array $map): string
{
    $defaults = defaultSelectionsForGenes($genes);
    $labels = [];
    foreach ($genes as $gene) {
        $geneId = $gene['id'];
        $state = $map[$geneId] ?? ($defaults[$geneId] ?? null);
        $default = $defaults[$geneId] ?? null;
        if ($state === null || $default === null) {
            continue;
        }
        if ($state === $default) {
            continue;
        }
        $labels[] = describeGeneState($gene, $state);
    }

    if (empty($labels)) {
        return 'Normal / Wildtyp';
    }

    return implode(', ', $labels);
}
