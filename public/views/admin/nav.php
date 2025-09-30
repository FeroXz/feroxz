<div class="admin-nav">
    <a href="<?= BASE_URL ?>/index.php?route=admin/dashboard" class="<?= $currentRoute === 'admin/dashboard' ? 'active' : '' ?>">Übersicht</a>
    <a href="<?= BASE_URL ?>/index.php?route=admin/animals" class="<?= $currentRoute === 'admin/animals' ? 'active' : '' ?>">Tiere</a>
    <a href="<?= BASE_URL ?>/index.php?route=admin/adoption" class="<?= $currentRoute === 'admin/adoption' ? 'active' : '' ?>">Tierabgabe</a>
    <a href="<?= BASE_URL ?>/index.php?route=admin/inquiries" class="<?= $currentRoute === 'admin/inquiries' ? 'active' : '' ?>">Anfragen</a>
    <?php if (is_authorized('can_manage_settings')): ?>
        <a href="<?= BASE_URL ?>/index.php?route=admin/settings" class="<?= $currentRoute === 'admin/settings' ? 'active' : '' ?>">Einstellungen</a>
    <?php endif; ?>
    <?php if (current_user()['role'] === 'admin'): ?>
        <a href="<?= BASE_URL ?>/index.php?route=admin/users" class="<?= $currentRoute === 'admin/users' ? 'active' : '' ?>">Benutzer</a>
    <?php endif; ?>
</div>
