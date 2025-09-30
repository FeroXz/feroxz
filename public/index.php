<?php
session_start();
require_once __DIR__ . '/../app/bootstrap.php';

$route = $_GET['route'] ?? 'home';
$GLOBALS['currentRoute'] = $route;

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
        view('auth/login', ['settings' => get_all_settings($pdo)]);
        break;

    case 'logout':
        logout();
        redirect('home');
        break;

    case 'home':
        $settings = get_all_settings($pdo);
        $animals = get_showcased_animals($pdo);
        $listings = get_public_listings($pdo);
        $latestNews = get_latest_published_news($pdo, 3);
        $careHighlights = array_slice(get_published_care_articles($pdo), 0, 3);
        view('home', compact('settings', 'animals', 'listings', 'latestNews', 'careHighlights'));
        break;

    case 'animals':
        $settings = get_all_settings($pdo);
        $animals = get_public_animals($pdo);
        view('animals/index', compact('settings', 'animals'));
        break;

    case 'my-animals':
        require_login();
        $settings = get_all_settings($pdo);
        $animals = get_user_animals($pdo, current_user()['id']);
        view('animals/my_animals', compact('settings', 'animals'));
        break;

    case 'breeding':
        require_login();
        $settings = get_all_settings($pdo);
        $breedingPlans = get_breeding_plans($pdo);
        view('breeding/index', compact('settings', 'breedingPlans'));
        break;

    case 'adoption':
        $settings = get_all_settings($pdo);
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
            } else {
                flash('error', 'Bitte alle Felder ausfüllen.');
            }
        }
        $flashSuccess = flash('success');
        $flashError = flash('error');
        view('adoption/index', compact('settings', 'listings', 'flashSuccess', 'flashError'));
        break;

    case 'page':
        $slug = $_GET['slug'] ?? '';
        $page = $slug ? get_page_by_slug($pdo, $slug) : null;
        if (!$page || (!$page['is_published'] && (!current_user() || !is_authorized('can_manage_settings')))) {
            http_response_code(404);
            view('errors/404', ['settings' => get_all_settings($pdo)]);
            break;
        }
        $settings = get_all_settings($pdo);
        view('pages/show', [
            'settings' => $settings,
            'page' => $page,
            'activePageSlug' => $page['slug'],
        ]);
        break;

    case 'news':
        $settings = get_all_settings($pdo);
        $slug = $_GET['slug'] ?? null;
        if ($slug) {
            $post = get_news_by_slug($pdo, $slug);
            if (!$post || (!$post['is_published'] && (!current_user() || !is_authorized('can_manage_settings')))) {
                http_response_code(404);
                view('errors/404', ['settings' => $settings]);
                break;
            }
            view('news/show', [
                'settings' => $settings,
                'post' => $post,
            ]);
        } else {
            $newsPosts = get_published_news($pdo);
            view('news/index', compact('settings', 'newsPosts'));
        }
        break;

    case 'care-guide':
        $settings = get_all_settings($pdo);
        $careArticles = get_published_care_articles($pdo);
        view('care/index', compact('settings', 'careArticles'));
        break;

    case 'care-article':
        $slug = $_GET['slug'] ?? '';
        $article = $slug ? get_care_article_by_slug($pdo, $slug) : null;
        if (!$article || (!$article['is_published'] && (!current_user() || !is_authorized('can_manage_settings')))) {
            http_response_code(404);
            view('errors/404', ['settings' => get_all_settings($pdo)]);
            break;
        }
        $settings = get_all_settings($pdo);
        view('care/show', [
            'settings' => $settings,
            'article' => $article,
            'activeCareSlug' => $article['slug'],
        ]);
        break;

    case 'admin/dashboard':
        require_login();
        $settings = get_all_settings($pdo);
        $animals = get_animals($pdo);
        $listings = get_listings($pdo);
        $inquiries = get_inquiries($pdo);
        $pages = get_pages($pdo);
        $newsPosts = get_news($pdo);
        $breedingPlans = get_breeding_plans($pdo);
        $careArticles = get_care_articles($pdo);
        view('admin/dashboard', compact('settings', 'animals', 'listings', 'inquiries', 'pages', 'newsPosts', 'breedingPlans', 'careArticles'));
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
        $settings = get_all_settings($pdo);
        $flashSuccess = flash('success');
        view('admin/settings', compact('settings', 'flashSuccess'));
        break;

    case 'admin/pages':
        require_login();
        if (!is_authorized('can_manage_settings')) {
            flash('error', 'Keine Berechtigung.');
            redirect('admin/dashboard');
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'title' => trim($_POST['title'] ?? ''),
                'slug' => trim($_POST['slug'] ?? ''),
                'content' => $_POST['content'] ?? '',
                'is_published' => isset($_POST['is_published']),
            ];
            if ($data['title'] && $data['content']) {
                if (!empty($_POST['id'])) {
                    update_page($pdo, (int)$_POST['id'], $data);
                    flash('success', 'Seite aktualisiert.');
                } else {
                    create_page($pdo, $data);
                    flash('success', 'Neue Seite angelegt.');
                }
                redirect('admin/pages');
            } else {
                flash('error', 'Titel und Inhalt werden benötigt.');
            }
        }
        if (isset($_GET['delete'])) {
            delete_page($pdo, (int)$_GET['delete']);
            flash('success', 'Seite gelöscht.');
            redirect('admin/pages');
        }
        $settings = get_all_settings($pdo);
        $pages = get_pages($pdo);
        $editPage = null;
        if (isset($_GET['edit'])) {
            $editPage = get_page($pdo, (int)$_GET['edit']);
        }
        $flashSuccess = flash('success');
        $flashError = flash('error');
        view('admin/pages', compact('settings', 'pages', 'editPage', 'flashSuccess', 'flashError'));
        break;

    case 'admin/news':
        require_login();
        if (!is_authorized('can_manage_settings')) {
            flash('error', 'Keine Berechtigung.');
            redirect('admin/dashboard');
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'title' => trim($_POST['title'] ?? ''),
                'slug' => trim($_POST['slug'] ?? ''),
                'excerpt' => $_POST['excerpt'] ?? null,
                'content' => $_POST['content'] ?? '',
                'is_published' => isset($_POST['is_published']),
                'published_at' => trim($_POST['published_at'] ?? ''),
            ];
            if ($data['title'] && $data['content']) {
                if (!empty($_POST['id'])) {
                    update_news($pdo, (int)$_POST['id'], $data);
                    flash('success', 'Neuigkeit aktualisiert.');
                } else {
                    create_news($pdo, $data);
                    flash('success', 'Neuigkeit veröffentlicht.');
                }
                redirect('admin/news');
            } else {
                flash('error', 'Titel und Inhalt werden benötigt.');
            }
        }
        if (isset($_GET['delete'])) {
            delete_news($pdo, (int)$_GET['delete']);
            flash('success', 'Neuigkeit gelöscht.');
            redirect('admin/news');
        }
        $settings = get_all_settings($pdo);
        $newsPosts = get_news($pdo);
        $editPost = null;
        if (isset($_GET['edit'])) {
            $editPost = get_news_post($pdo, (int)$_GET['edit']);
        }
        $flashSuccess = flash('success');
        $flashError = flash('error');
        view('admin/news', compact('settings', 'newsPosts', 'editPost', 'flashSuccess', 'flashError'));
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
        $settings = get_all_settings($pdo);
        $editAnimal = null;
        if (isset($_GET['edit'])) {
            $editAnimal = get_animal($pdo, (int)$_GET['edit']);
        }
        $flashSuccess = flash('success');
        view('admin/animals', compact('animals', 'users', 'editAnimal', 'flashSuccess', 'settings'));
        break;

    case 'admin/breeding':
        require_login();
        if (!is_authorized('can_manage_animals')) {
            flash('error', 'Keine Berechtigung.');
            redirect('admin/dashboard');
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $formType = $_POST['form'] ?? 'plan';
            if ($formType === 'parent') {
                $planId = (int)($_POST['plan_id'] ?? 0);
                if ($planId) {
                    $parentType = $_POST['parent_type'] ?? 'animal';
                    $data = [
                        'plan_id' => $planId,
                        'parent_type' => $parentType === 'virtual' ? 'virtual' : 'animal',
                        'animal_id' => $_POST['animal_id'] ?? null,
                        'name' => trim($_POST['name'] ?? ''),
                        'sex' => trim($_POST['sex'] ?? ''),
                        'species' => trim($_POST['species'] ?? ''),
                        'genetics' => trim($_POST['genetics'] ?? ''),
                        'notes' => $_POST['notes'] ?? null,
                    ];
                    if ($data['parent_type'] === 'animal' && empty($data['animal_id'])) {
                        flash('error', 'Bitte ein Tier auswählen oder als virtuell kennzeichnen.');
                    } else {
                        if ($data['parent_type'] === 'virtual' && !$data['name']) {
                            $data['name'] = 'Virtueller Elternteil';
                        }
                        add_breeding_parent($pdo, $data);
                        flash('success', 'Elternteil hinzugefügt.');
                    }
                }
                redirect('admin/breeding', ['edit_plan' => $planId]);
            } else {
                $data = [
                    'title' => trim($_POST['title'] ?? ''),
                    'season' => trim($_POST['season'] ?? ''),
                    'notes' => $_POST['notes'] ?? null,
                    'expected_genetics' => $_POST['expected_genetics'] ?? null,
                    'incubation_notes' => $_POST['incubation_notes'] ?? null,
                ];
                if ($data['title']) {
                    if (!empty($_POST['id'])) {
                        update_breeding_plan($pdo, (int)$_POST['id'], $data);
                        flash('success', 'Zuchtplan aktualisiert.');
                        redirect('admin/breeding', ['edit_plan' => (int)$_POST['id']]);
                    } else {
                        $planId = create_breeding_plan($pdo, $data);
                        flash('success', 'Zuchtplan erstellt.');
                        redirect('admin/breeding', ['edit_plan' => $planId]);
                    }
                } else {
                    flash('error', 'Titel wird benötigt.');
                }
            }
        }
        if (isset($_GET['delete_plan'])) {
            delete_breeding_plan($pdo, (int)$_GET['delete_plan']);
            flash('success', 'Zuchtplan gelöscht.');
            redirect('admin/breeding');
        }
        if (isset($_GET['delete_parent'])) {
            delete_breeding_parent($pdo, (int)$_GET['delete_parent']);
            flash('success', 'Elternteil entfernt.');
            redirect('admin/breeding', ['edit_plan' => (int)($_GET['plan'] ?? 0)]);
        }
        $settings = get_all_settings($pdo);
        $animals = get_animals($pdo);
        $breedingPlans = get_breeding_plans($pdo);
        $editPlan = null;
        if (isset($_GET['edit_plan'])) {
            $editPlan = get_breeding_plan($pdo, (int)$_GET['edit_plan']);
        }
        $flashSuccess = flash('success');
        $flashError = flash('error');
        view('admin/breeding', compact('settings', 'animals', 'breedingPlans', 'editPlan', 'flashSuccess', 'flashError'));
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
        $settings = get_all_settings($pdo);
        $editListing = null;
        if (isset($_GET['edit'])) {
            $editListing = get_listing($pdo, (int)$_GET['edit']);
        }
        $flashSuccess = flash('success');
        view('admin/adoption', compact('listings', 'animals', 'editListing', 'flashSuccess', 'settings'));
        break;

    case 'admin/care':
        require_login();
        if (!is_authorized('can_manage_settings')) {
            flash('error', 'Keine Berechtigung.');
            redirect('admin/dashboard');
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'title' => trim($_POST['title'] ?? ''),
                'slug' => trim($_POST['slug'] ?? ''),
                'summary' => $_POST['summary'] ?? null,
                'content' => $_POST['content'] ?? '',
                'is_published' => isset($_POST['is_published']),
            ];
            if ($data['title'] && $data['content']) {
                if (!empty($_POST['id'])) {
                    update_care_article($pdo, (int)$_POST['id'], $data);
                    flash('success', 'Artikel aktualisiert.');
                } else {
                    create_care_article($pdo, $data);
                    flash('success', 'Artikel erstellt.');
                }
                redirect('admin/care');
            } else {
                flash('error', 'Titel und Inhalt werden benötigt.');
            }
        }
        if (isset($_GET['delete'])) {
            delete_care_article($pdo, (int)$_GET['delete']);
            flash('success', 'Artikel gelöscht.');
            redirect('admin/care');
        }
        $settings = get_all_settings($pdo);
        $careArticles = get_care_articles($pdo);
        $editArticle = null;
        if (isset($_GET['edit'])) {
            $editArticle = get_care_article($pdo, (int)$_GET['edit']);
        }
        $flashSuccess = flash('success');
        $flashError = flash('error');
        view('admin/care', compact('settings', 'careArticles', 'editArticle', 'flashSuccess', 'flashError'));
        break;

    case 'admin/inquiries':
        require_login();
        if (!is_authorized('can_manage_adoptions')) {
            flash('error', 'Keine Berechtigung.');
            redirect('admin/dashboard');
        }
        $inquiries = get_inquiries($pdo);
        $settings = get_all_settings($pdo);
        view('admin/inquiries', compact('inquiries', 'settings'));
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
        $settings = get_all_settings($pdo);
        $editUser = null;
        if (isset($_GET['edit'])) {
            $editUser = get_user($pdo, (int)$_GET['edit']);
        }
        $flashSuccess = flash('success');
        view('admin/users', compact('users', 'editUser', 'flashSuccess', 'settings'));
        break;

    default:
        http_response_code(404);
        view('errors/404', ['settings' => get_all_settings($pdo)]);
}
