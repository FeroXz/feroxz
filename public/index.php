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
        view('home', compact('settings', 'animals', 'listings'));
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

    case 'admin/dashboard':
        require_login();
        $settings = get_all_settings($pdo);
        $animals = get_animals($pdo);
        $listings = get_listings($pdo);
        $inquiries = get_inquiries($pdo);
        view('admin/dashboard', compact('settings', 'animals', 'listings', 'inquiries'));
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
