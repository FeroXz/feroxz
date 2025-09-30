<div class="admin-nav">
    <a href="<?= route_url('admin/dashboard') ?>" class="<?= $currentRoute === 'admin/dashboard' ? 'active' : '' ?>">Übersicht</a>
    <a href="<?= route_url('admin/animals') ?>" class="<?= $currentRoute === 'admin/animals' ? 'active' : '' ?>">Tiere</a>
    <a href="<?= route_url('admin/adoption') ?>" class="<?= $currentRoute === 'admin/adoption' ? 'active' : '' ?>">Tierabgabe</a>
    <a href="<?= route_url('admin/inquiries') ?>" class="<?= $currentRoute === 'admin/inquiries' ? 'active' : '' ?>">Anfragen</a>
    <a href="<?= route_url('admin/gallery') ?>" class="<?= $currentRoute === 'admin/gallery' ? 'active' : '' ?>">Galerie</a>
    <?php if (is_authorized('can_manage_settings')): ?>
        <a href="<?= route_url('admin/pages') ?>" class="<?= $currentRoute === 'admin/pages' ? 'active' : '' ?>">Seiten</a>
        <a href="<?= route_url('admin/posts') ?>" class="<?= $currentRoute === 'admin/posts' ? 'active' : '' ?>">Beiträge</a>
        <a href="<?= route_url('admin/menu') ?>" class="<?= $currentRoute === 'admin/menu' ? 'active' : '' ?>">Menü</a>
        <a href="<?= route_url('admin/care-guides') ?>" class="<?= $currentRoute === 'admin/care-guides' ? 'active' : '' ?>">Pflege</a>
        <a href="<?= route_url('admin/genetics') ?>" class="<?= $currentRoute === 'admin/genetics' ? 'active' : '' ?>">Genetik</a>
        <a href="<?= route_url('admin/settings') ?>" class="<?= $currentRoute === 'admin/settings' ? 'active' : '' ?>">Einstellungen</a>
    <?php endif; ?>
    <?php if (current_user()['role'] === 'admin'): ?>
        <a href="<?= route_url('admin/users') ?>" class="<?= $currentRoute === 'admin/users' ? 'active' : '' ?>">Benutzer</a>
    <?php endif; ?>
</div>
