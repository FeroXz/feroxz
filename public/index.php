<?php
session_start();
require_once __DIR__ . '/../app/bootstrap.php';

$route = $_GET['route'] ?? 'home';
$GLOBALS['currentRoute'] = $route;

$settings = get_all_settings($pdo);
$menuItems = get_visible_menu($pdo);
share_view_data([
    'settings' => $settings,
    'menuItems' => $menuItems,
    'currentUser' => current_user(),
]);

switch ($route) {
    case 'login':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username'] ?? '');
            $password = $_POST['password'] ?? '';
            if (authenticate($pdo, $username, $password)) {
                flash('success', 'Willkommen zurück!');
                redirect('admin/dashboard');
            }
            flash('error', 'Ungültige Zugangsdaten.');
        }
        $flashError = flash('error');
        $flashSuccess = flash('success');
        view('auth/login', compact('flashError', 'flashSuccess'));
        break;

    case 'logout':
        logout();
        redirect('home');
        break;

    case 'home':
        $animals = get_showcased_animals($pdo);
        $listings = get_public_listings($pdo);
        $posts = array_slice(get_published_posts($pdo), 0, 3);
        $guides = get_care_guides($pdo);
        view('home', compact('animals', 'listings', 'posts', 'guides'));
        break;

    case 'animals':
        $animals = get_public_animals($pdo);
        view('animals/index', compact('animals'));
        break;

    case 'my-animals':
        require_login();
        $animals = get_user_animals($pdo, current_user()['id']);
        $species = get_genetic_species($pdo);
        $geneMap = [];
        foreach ($species as $spec) {
            foreach (get_genes_for_species($pdo, $spec['id']) as $gene) {
                $geneMap[$gene['slug']] = $gene;
            }
        }
        view('animals/my_animals', compact('animals', 'geneMap'));
        break;

    case 'adoption':
        $listings = get_public_listings($pdo);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $listingId = (int)($_POST['listing_id'] ?? 0);
            $name = trim($_POST['name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $message = trim($_POST['message'] ?? '');
            if ($listingId && $name && $email && $message) {
                create_inquiry($pdo, [
                    'listing_id' => $listingId,
                    'interested_in' => $_POST['interested_in'] ?? null,
                    'sender_name' => $name,
                    'sender_email' => $email,
                    'message' => $message,
                ]);
                flash('success', 'Anfrage wurde gesendet.');
                redirect('adoption');
            }
            flash('error', 'Bitte alle Pflichtfelder ausfüllen.');
        }
        $flashSuccess = flash('success');
        $flashError = flash('error');
        view('adoption/index', compact('listings', 'flashSuccess', 'flashError'));
        break;

    case 'page':
        $slug = $_GET['slug'] ?? '';
        $page = $slug ? get_page_by_slug($pdo, $slug) : null;
        if (!$page) {
            http_response_code(404);
            view('errors/404', []);
            break;
        }
        view('pages/show', compact('page'));
        break;

    case 'blog':
        $posts = get_published_posts($pdo);
        view('posts/index', compact('posts'));
        break;

    case 'post':
        $slug = $_GET['slug'] ?? '';
        $post = $slug ? get_post_by_slug($pdo, $slug) : null;
        if (!$post) {
            http_response_code(404);
            view('errors/404', []);
            break;
        }
        view('posts/show', compact('post'));
        break;

    case 'gallery':
        $items = get_gallery_items($pdo);
        view('gallery/index', compact('items'));
        break;

    case 'care-guides':
        $guides = get_care_guides($pdo);
        view('care_guides/index', compact('guides'));
        break;

    case 'care-guide':
        $slug = $_GET['slug'] ?? '';
        $guide = $slug ? get_care_guide_by_slug($pdo, $slug) : null;
        if (!$guide) {
            http_response_code(404);
            view('errors/404', []);
            break;
        }
        view('care_guides/show', compact('guide'));
        break;

    case 'genetics':
        $species = get_genetic_species($pdo);
        view('genetics/index', compact('species'));
        break;

    case 'genetics/species':
        $slug = $_GET['slug'] ?? '';
        $species = $slug ? get_genetic_species_by_slug($pdo, $slug) : null;
        if (!$species) {
            http_response_code(404);
            view('errors/404', []);
            break;
        }
        $genes = get_genes_for_species($pdo, (int)$species['id']);
        view('genetics/species', compact('species', 'genes'));
        break;

    case 'genetics/gene':
        $speciesSlug = $_GET['species'] ?? '';
        $geneSlug = $_GET['gene'] ?? '';
        $species = $speciesSlug ? get_genetic_species_by_slug($pdo, $speciesSlug) : null;
        $gene = ($species && $geneSlug) ? get_gene_by_slug($pdo, (int)$species['id'], $geneSlug) : null;
        if (!$species || !$gene) {
            http_response_code(404);
            view('errors/404', []);
            break;
        }
        view('genetics/gene', compact('species', 'gene'));
        break;

    case 'genetics/calculator':
        $speciesList = get_genetic_species($pdo);
        $speciesSlug = $_REQUEST['species'] ?? ($speciesList[0]['slug'] ?? '');
        $species = $speciesSlug ? get_genetic_species_by_slug($pdo, $speciesSlug) : null;
        if (!$species && !empty($speciesList)) {
            $species = $speciesList[0];
            $speciesSlug = $species['slug'];
        }
        $genes = $species ? get_genes_for_species($pdo, (int)$species['id']) : [];
        $geneOptions = [];
        foreach ($genes as $gene) {
            $geneOptions[$gene['slug']] = [
                'name' => $gene['name'],
                'options' => gene_state_options($gene),
            ];
        }

        $availableAnimals = [];
        $speciesMatches = $species ? array_filter([$species['name'] ?? null, $species['scientific_name'] ?? null]) : [];
        foreach (get_animals($pdo) as $animal) {
            if (empty($animal['genetics_profile'])) {
                continue;
            }
            if ($species && !in_array($animal['species'], $speciesMatches, true)) {
                continue;
            }
            if (!empty($animal['is_private']) && (!current_user() || $animal['owner_id'] !== current_user()['id'])) {
                continue;
            }
            $animal['profile_values'] = decode_genetics_profile($animal['genetics_profile']);
            $availableAnimals[] = $animal;
        }

        $parentASelection = $_POST['parent_a'] ?? [];
        $parentBSelection = $_POST['parent_b'] ?? [];

        $selectedAnimalA = (int)($_POST['parent_a_animal'] ?? 0);
        $selectedAnimalB = (int)($_POST['parent_b_animal'] ?? 0);

        foreach ($availableAnimals as $animal) {
            if ($animal['id'] === $selectedAnimalA) {
                $parentASelection = $animal['profile_values'];
            }
            if ($animal['id'] === $selectedAnimalB) {
                $parentBSelection = $animal['profile_values'];
            }
        }

        $results = null;
        if ($species) {
            $results = compute_genetics($pdo, (int)$species['id'], (array)$parentASelection, (array)$parentBSelection);
        }

        view('genetics/calculator', compact('speciesList', 'species', 'geneOptions', 'parentASelection', 'parentBSelection', 'availableAnimals', 'selectedAnimalA', 'selectedAnimalB', 'results'));
        break;

    case 'admin/dashboard':
        require_login();
        $animals = get_animals($pdo);
        $listings = get_listings($pdo);
        $inquiries = get_inquiries($pdo);
        $posts = get_posts($pdo);
        $pages = get_pages($pdo);
        $gallery = get_gallery_items($pdo);
        view('admin/dashboard', compact('animals', 'listings', 'inquiries', 'posts', 'pages', 'gallery'));
        break;

    case 'admin/settings':
        require_login();
        if (!is_authorized('can_manage_settings')) {
            flash('error', 'Keine Berechtigung.');
            redirect('admin/dashboard');
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            update_settings($pdo, [
                'site_title' => $_POST['site_title'] ?? '',
                'site_tagline' => $_POST['site_tagline'] ?? '',
                'hero_intro' => $_POST['hero_intro'] ?? '',
                'adoption_intro' => $_POST['adoption_intro'] ?? '',
                'footer_text' => $_POST['footer_text'] ?? '',
                'contact_email' => $_POST['contact_email'] ?? '',
            ]);
            flash('success', 'Einstellungen gespeichert.');
            redirect('admin/settings');
        }
        $flashSuccess = flash('success');
        view('admin/settings', compact('flashSuccess'));
        break;

    case 'admin/animals':
        require_login();
        if (!is_authorized('can_manage_animals')) {
            flash('error', 'Keine Berechtigung.');
            redirect('admin/dashboard');
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $_POST;
            $data['is_private'] = isset($_POST['is_private']);
            $data['is_showcased'] = isset($_POST['is_showcased']);
            $data['owner_id'] = $data['owner_id'] ?? null;
            $data['genetics_profile'] = null;
            if (!empty($_POST['genetics_profile'])) {
                $data['genetics_profile'] = json_encode(array_values(array_filter((array)$_POST['genetics_profile'])));
            }
            if (!empty($_FILES['image']['name'])) {
                $upload = handle_upload($_FILES['image']);
                if ($upload) {
                    $data['image_path'] = $upload;
                }
            }
            if (!empty($data['id'])) {
                update_animal($pdo, (int)$data['id'], $data);
                flash('success', 'Tier aktualisiert.');
            } else {
                create_animal($pdo, $data);
                flash('success', 'Tier angelegt.');
            }
            redirect('admin/animals');
        }
        if (isset($_GET['delete'])) {
            delete_animal($pdo, (int)$_GET['delete']);
            flash('success', 'Tier gelöscht.');
            redirect('admin/animals');
        }
        $animals = get_animals($pdo);
        $users = get_users($pdo);
        $editAnimal = null;
        if (isset($_GET['edit'])) {
            $editAnimal = get_animal($pdo, (int)$_GET['edit']);
            if ($editAnimal && !empty($editAnimal['genetics_profile'])) {
                $editAnimal['genetics_profile'] = decode_genetics_profile($editAnimal['genetics_profile']);
            }
        }
        $species = get_genetic_species($pdo);
        $geneOptions = [];
        foreach ($species as $spec) {
            $geneOptions[$spec['slug']] = [];
            foreach (get_genes_for_species($pdo, $spec['id']) as $gene) {
                $geneOptions[$spec['slug']][] = gene_state_options($gene);
            }
        }
        $flashSuccess = flash('success');
        view('admin/animals', compact('animals', 'users', 'editAnimal', 'flashSuccess', 'species', 'geneOptions'));
        break;

    case 'admin/adoption':
        require_login();
        if (!is_authorized('can_manage_adoptions')) {
            flash('error', 'Keine Berechtigung.');
            redirect('admin/dashboard');
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $_POST;
            if (!empty($_FILES['image']['name'])) {
                $upload = handle_upload($_FILES['image']);
                if ($upload) {
                    $data['image_path'] = $upload;
                }
            }
            if (!empty($data['id'])) {
                update_listing($pdo, (int)$data['id'], $data);
                flash('success', 'Abgabeintrag aktualisiert.');
            } else {
                create_listing($pdo, $data);
                flash('success', 'Abgabeintrag erstellt.');
            }
            redirect('admin/adoption');
        }
        if (isset($_GET['delete'])) {
            delete_listing($pdo, (int)$_GET['delete']);
            flash('success', 'Eintrag gelöscht.');
            redirect('admin/adoption');
        }
        $listings = get_listings($pdo);
        $animals = get_animals($pdo);
        $editListing = null;
        if (isset($_GET['edit'])) {
            $editListing = get_listing($pdo, (int)$_GET['edit']);
        }
        $flashSuccess = flash('success');
        view('admin/adoption', compact('listings', 'animals', 'editListing', 'flashSuccess'));
        break;

    case 'admin/inquiries':
        require_login();
        if (!is_authorized('can_manage_adoptions')) {
            flash('error', 'Keine Berechtigung.');
            redirect('admin/dashboard');
        }
        $inquiries = get_inquiries($pdo);
        view('admin/inquiries', compact('inquiries'));
        break;

    case 'admin/users':
        require_login();
        if (current_user()['role'] !== 'admin') {
            flash('error', 'Keine Berechtigung.');
            redirect('admin/dashboard');
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $_POST;
            if (!empty($data['id'])) {
                update_user($pdo, (int)$data['id'], $data);
                flash('success', 'Benutzer aktualisiert.');
            } else {
                create_user($pdo, $data);
                flash('success', 'Benutzer erstellt.');
            }
            redirect('admin/users');
        }
        if (isset($_GET['delete'])) {
            delete_user($pdo, (int)$_GET['delete']);
            flash('success', 'Benutzer gelöscht.');
            redirect('admin/users');
        }
        $users = get_users($pdo);
        $editUser = null;
        if (isset($_GET['edit'])) {
            $editUser = get_user($pdo, (int)$_GET['edit']);
        }
        $flashSuccess = flash('success');
        view('admin/users', compact('users', 'editUser', 'flashSuccess'));
        break;

    case 'admin/pages':
        require_login();
        if (!is_authorized('can_manage_settings')) {
            flash('error', 'Keine Berechtigung.');
            redirect('admin/dashboard');
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = trim($_POST['title'] ?? '');
            $providedSlug = trim($_POST['slug'] ?? '');
            $data = [
                'title' => $title,
                'slug' => slugify($providedSlug !== '' ? $providedSlug : ($title !== '' ? $title : 'seite')),
                'excerpt' => $_POST['excerpt'] ?? '',
                'content' => $_POST['content'] ?? '',
                'is_published' => !empty($_POST['is_published']),
            ];
            if (!empty($_POST['id'])) {
                update_page($pdo, (int)$_POST['id'], $data);
                flash('success', 'Seite aktualisiert.');
            } else {
                create_page($pdo, $data);
                flash('success', 'Seite erstellt.');
            }
            redirect('admin/pages');
        }
        if (isset($_GET['delete'])) {
            delete_page($pdo, (int)$_GET['delete']);
            flash('success', 'Seite gelöscht.');
            redirect('admin/pages');
        }
        $pages = get_pages($pdo);
        $editPage = null;
        if (isset($_GET['edit'])) {
            $editPage = get_page($pdo, (int)$_GET['edit']);
        }
        $flashSuccess = flash('success');
        view('admin/pages', compact('pages', 'editPage', 'flashSuccess'));
        break;

    case 'admin/posts':
        require_login();
        if (!is_authorized('can_manage_settings')) {
            flash('error', 'Keine Berechtigung.');
            redirect('admin/dashboard');
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = trim($_POST['title'] ?? '');
            $providedSlug = trim($_POST['slug'] ?? '');
            $data = [
                'title' => $title,
                'slug' => slugify($providedSlug !== '' ? $providedSlug : ($title !== '' ? $title : 'beitrag')),
                'excerpt' => $_POST['excerpt'] ?? '',
                'content' => $_POST['content'] ?? '',
                'is_published' => !empty($_POST['is_published']),
                'published_at' => $_POST['published_at'] ?? null,
            ];
            if (!empty($data['published_at'])) {
                $timestamp = strtotime($data['published_at']);
                $data['published_at'] = $timestamp ? date('c', $timestamp) : null;
            }
            if (!empty($_POST['id'])) {
                update_post($pdo, (int)$_POST['id'], $data);
                flash('success', 'Beitrag aktualisiert.');
            } else {
                create_post($pdo, $data);
                flash('success', 'Beitrag erstellt.');
            }
            redirect('admin/posts');
        }
        if (isset($_GET['delete'])) {
            delete_post($pdo, (int)$_GET['delete']);
            flash('success', 'Beitrag gelöscht.');
            redirect('admin/posts');
        }
        $posts = get_posts($pdo);
        $editPost = null;
        if (isset($_GET['edit'])) {
            $editPost = get_post($pdo, (int)$_GET['edit']);
        }
        $flashSuccess = flash('success');
        view('admin/posts', compact('posts', 'editPost', 'flashSuccess'));
        break;

    case 'admin/gallery':
        require_login();
        if (!is_authorized('can_manage_animals')) {
            flash('error', 'Keine Berechtigung.');
            redirect('admin/dashboard');
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'title' => trim($_POST['title'] ?? ''),
                'description' => $_POST['description'] ?? '',
                'image_path' => $_POST['image_path'] ?? '',
            ];
            if (!empty($_FILES['image']['name'])) {
                $upload = handle_upload($_FILES['image']);
                if ($upload) {
                    $data['image_path'] = $upload;
                }
            }
            if (!empty($_POST['id'])) {
                update_gallery_item($pdo, (int)$_POST['id'], $data);
                flash('success', 'Galerieeintrag aktualisiert.');
            } else {
                create_gallery_item($pdo, $data);
                flash('success', 'Galerieeintrag erstellt.');
            }
            redirect('admin/gallery');
        }
        if (isset($_GET['delete'])) {
            delete_gallery_item($pdo, (int)$_GET['delete']);
            flash('success', 'Galerieeintrag gelöscht.');
            redirect('admin/gallery');
        }
        $items = get_gallery_items($pdo);
        $editItem = null;
        if (isset($_GET['edit'])) {
            $editItem = get_gallery_item($pdo, (int)$_GET['edit']);
        }
        $flashSuccess = flash('success');
        view('admin/gallery', compact('items', 'editItem', 'flashSuccess'));
        break;

    case 'admin/menu':
        require_login();
        if (!is_authorized('can_manage_settings')) {
            flash('error', 'Keine Berechtigung.');
            redirect('admin/dashboard');
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'label' => trim($_POST['label'] ?? ''),
                'route' => $_POST['route'] ?? null,
                'page_slug' => $_POST['page_slug'] ?? null,
                'external_url' => $_POST['external_url'] ?? null,
                'is_visible' => !empty($_POST['is_visible']),
                'position' => (int)($_POST['position'] ?? 0),
            ];
            if (!empty($_POST['id'])) {
                update_menu_item($pdo, (int)$_POST['id'], $data);
                flash('success', 'Menüpunkt aktualisiert.');
            } else {
                create_menu_item($pdo, $data);
                flash('success', 'Menüpunkt erstellt.');
            }
            redirect('admin/menu');
        }
        if (isset($_GET['delete'])) {
            delete_menu_item($pdo, (int)$_GET['delete']);
            flash('success', 'Menüpunkt gelöscht.');
            redirect('admin/menu');
        }
        $items = get_menu_items($pdo);
        $pages = get_pages($pdo);
        $editItem = null;
        if (isset($_GET['edit'])) {
            $editId = (int)$_GET['edit'];
            foreach ($items as $item) {
                if ((int)$item['id'] === $editId) {
                    $editItem = $item;
                    break;
                }
            }
        }
        $flashSuccess = flash('success');
        view('admin/menu', compact('items', 'pages', 'flashSuccess', 'editItem'));
        break;

    case 'admin/care-guides':
        require_login();
        if (!is_authorized('can_manage_settings')) {
            flash('error', 'Keine Berechtigung.');
            redirect('admin/dashboard');
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $speciesName = trim($_POST['species'] ?? '');
            $providedSlug = trim($_POST['slug'] ?? '');
            $data = [
                'species' => $speciesName,
                'slug' => slugify($providedSlug !== '' ? $providedSlug : ($speciesName !== '' ? $speciesName : 'pflege')),
                'headline' => $_POST['headline'] ?? '',
                'summary' => $_POST['summary'] ?? '',
                'habitat' => $_POST['habitat'] ?? '',
                'lighting' => $_POST['lighting'] ?? '',
                'diet' => $_POST['diet'] ?? '',
                'enrichment' => $_POST['enrichment'] ?? '',
                'health' => $_POST['health'] ?? '',
                'breeding' => $_POST['breeding'] ?? '',
            ];
            if (!empty($_POST['id'])) {
                update_care_guide($pdo, (int)$_POST['id'], $data);
                flash('success', 'Pflegeleitfaden aktualisiert.');
            } else {
                create_care_guide($pdo, $data);
                flash('success', 'Pflegeleitfaden erstellt.');
            }
            redirect('admin/care-guides');
        }
        if (isset($_GET['delete'])) {
            delete_care_guide($pdo, (int)$_GET['delete']);
            flash('success', 'Pflegeleitfaden gelöscht.');
            redirect('admin/care-guides');
        }
        $guides = get_care_guides($pdo);
        $editGuide = null;
        if (isset($_GET['edit'])) {
            $editGuide = get_care_guide($pdo, (int)$_GET['edit']);
        }
        $flashSuccess = flash('success');
        view('admin/care_guides', compact('guides', 'editGuide', 'flashSuccess'));
        break;

    case 'admin/genetics':
        require_login();
        if (!is_authorized('can_manage_settings')) {
            flash('error', 'Keine Berechtigung.');
            redirect('admin/dashboard');
        }
        if (isset($_POST['update_species'])) {
            $speciesId = (int)$_POST['species_id'];
            $species = get_genetic_species_by_id($pdo, $speciesId);
            if ($species) {
                $stmt = $pdo->prepare('UPDATE genetic_species SET name = :name, scientific_name = :scientific_name, description = :description WHERE id = :id');
                $stmt->execute([
                    'name' => trim($_POST['name'] ?? $species['name']),
                    'scientific_name' => trim($_POST['scientific_name'] ?? $species['scientific_name']),
                    'description' => $_POST['description'] ?? $species['description'],
                    'id' => $speciesId,
                ]);
                flash('success', 'Spezies aktualisiert.');
                redirect('admin/genetics', ['species' => $speciesId]);
            }
        } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            $providedSlug = trim($_POST['slug'] ?? '');
            $data = [
                'species_id' => (int)($_POST['species_id'] ?? 0),
                'name' => $name,
                'slug' => slugify($providedSlug !== '' ? $providedSlug : ($name !== '' ? $name : 'gen')),
                'inheritance' => $_POST['inheritance'] ?? 'recessive',
                'description' => $_POST['description'] ?? '',
                'visual_label' => $_POST['visual_label'] ?? '',
                'heterozygous_label' => $_POST['heterozygous_label'] ?? '',
                'homozygous_label' => $_POST['homozygous_label'] ?? '',
                'wild_label' => $_POST['wild_label'] ?? 'Wildtyp',
            ];
            if (!empty($_POST['id'])) {
                update_gene($pdo, (int)$_POST['id'], $data);
                flash('success', 'Gen aktualisiert.');
            } else {
                create_gene($pdo, $data);
                flash('success', 'Gen erstellt.');
            }
            redirect('admin/genetics', ['species' => $data['species_id']]);
        }
        if (isset($_GET['delete'])) {
            $gene = get_gene($pdo, (int)$_GET['delete']);
            if ($gene) {
                delete_gene($pdo, (int)$gene['id']);
                flash('success', 'Gen gelöscht.');
                redirect('admin/genetics', ['species' => $gene['species_id']]);
            }
        }
        $speciesList = get_genetic_species($pdo);
        $selectedSpeciesId = isset($_GET['species']) ? (int)$_GET['species'] : ($speciesList[0]['id'] ?? 0);
        $selectedSpecies = $selectedSpeciesId ? get_genetic_species_by_id($pdo, $selectedSpeciesId) : null;
        $genes = $selectedSpecies ? get_genes_for_species($pdo, $selectedSpeciesId) : [];
        $editGene = null;
        if (isset($_GET['edit'])) {
            $editGene = get_gene($pdo, (int)$_GET['edit']);
            if ($editGene) {
                $selectedSpeciesId = (int)$editGene['species_id'];
                $selectedSpecies = get_genetic_species_by_id($pdo, $selectedSpeciesId);
                $genes = get_genes_for_species($pdo, $selectedSpeciesId);
            }
        }
        $flashSuccess = flash('success');
        view('admin/genetics', compact('speciesList', 'selectedSpecies', 'genes', 'editGene', 'selectedSpeciesId', 'flashSuccess'));
        break;

    default:
        http_response_code(404);
        view('errors/404', []);
}
