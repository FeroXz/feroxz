</main>
<footer class="site-footer">
    <div class="site-footer__inner">
        <div class="content-prose">
            <?= nl2br(htmlspecialchars($settings['footer_text'] ?? 'FeroxZ Reptile Center – verantwortungsvolle Haltung und Genetikberatung für Bartagamen.')) ?>
        </div>
        <div class="site-footer__meta">
            <span>© <?= date('Y') ?> <?= htmlspecialchars($settings['site_title'] ?? APP_NAME) ?></span>
            <span aria-hidden="true">•</span>
            <span>Alle Rechte vorbehalten.</span>
            <span aria-hidden="true">•</span>
            <span>Version <?= htmlspecialchars(APP_VERSION) ?></span>
        </div>
    </div>
</footer>
<script>
    (function () {
        const nav = document.querySelector('[data-nav]');
        const toggle = document.querySelector('[data-nav-toggle]');
        const groups = document.querySelectorAll('[data-nav-group]');
        if (toggle && nav) {
            toggle.addEventListener('click', () => {
                const isOpen = nav.getAttribute('data-open') === 'true';
                nav.setAttribute('data-open', isOpen ? 'false' : 'true');
                toggle.setAttribute('aria-expanded', isOpen ? 'false' : 'true');
            });
            nav.querySelectorAll('a').forEach((link) => {
                link.addEventListener('click', () => {
                    if (window.innerWidth <= 1080) {
                        nav.setAttribute('data-open', 'false');
                        toggle.setAttribute('aria-expanded', 'false');
                    }
                });
            });
        }
        groups.forEach((group) => {
            const trigger = group.querySelector('[data-nav-trigger]');
            const dropdown = group.querySelector('.site-nav__dropdown');
            if (!trigger || !dropdown) {
                return;
            }
            trigger.addEventListener('click', (event) => {
                event.preventDefault();
                const isOpen = group.classList.contains('open');
                document.querySelectorAll('.site-nav__group.open').forEach((openGroup) => {
                    if (openGroup !== group) {
                        openGroup.classList.remove('open');
                        const openTrigger = openGroup.querySelector('[data-nav-trigger]');
                        openTrigger?.setAttribute('aria-expanded', 'false');
                    }
                });
                group.classList.toggle('open', !isOpen);
                trigger.setAttribute('aria-expanded', !isOpen ? 'true' : 'false');
            });
            group.addEventListener('mouseleave', () => {
                group.classList.remove('open');
                trigger.setAttribute('aria-expanded', 'false');
            });
            group.addEventListener('keyup', (event) => {
                if (event.key === 'Escape') {
                    group.classList.remove('open');
                    trigger.setAttribute('aria-expanded', 'false');
                    trigger.focus();
                }
            });
        });
    })();
</script>
<?php
    $schemaBlocks = [
        [
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => ORG_NAME,
            'url' => 'https://' . SITE_DOMAIN,
            'logo' => ORG_LOGO_URL,
            'email' => CONTACT_EMAIL,
            'sameAs' => ORG_SAME_AS,
        ],
    ];

    if (!empty($pageMeta['breadcrumbs'])) {
        $schemaBlocks[] = [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => array_map(static function ($crumb) {
                return [
                    '@type' => 'ListItem',
                    'position' => $crumb['position'],
                    'name' => $crumb['name'],
                    'item' => $crumb['url'],
                ];
            }, $pageMeta['breadcrumbs']),
        ];
    }

    if (!empty($pageMeta['schema'])) {
        foreach ($pageMeta['schema'] as $schemaBlock) {
            $schemaBlocks[] = $schemaBlock;
        }
    }

    echo render_structured_data($schemaBlocks);
?>
<?php if (($currentRoute ?? '') === 'genetics'): ?>
    <script src="<?= asset('genetics.js') ?>"></script>
<?php endif; ?>
<?php if (isset($currentRoute) && str_starts_with($currentRoute, 'admin/')): ?>
    <script src="<?= asset('admin.js') ?>"></script>
<?php endif; ?>
</body>
</html>
