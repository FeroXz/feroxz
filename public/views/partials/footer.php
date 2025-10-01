    </div>
</main>
<footer class="border-t border-white/10 bg-night-950/90 py-12">
    <div class="mx-auto grid w-full max-w-7xl gap-10 px-4 text-sm text-slate-300 sm:px-6 lg:grid-cols-[1fr_auto] lg:items-start lg:px-8">
        <div class="space-y-4">
            <div>
                <span class="inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/5 px-3 py-1 text-xs font-semibold uppercase tracking-[0.2em] text-brand-200">FeroxZ Netzwerk</span>
                <h2 class="mt-4 text-2xl font-semibold text-white">Pflege, Genetik &amp; Wissen aus einer Hand</h2>
            </div>
            <div class="prose prose-invert max-w-none text-slate-300">
                <?= nl2br(htmlspecialchars($settings['footer_text'] ?? 'Ganzheitliche Betreuung und dokumentierte Genetik für verantwortungsvolle Terraristik.')) ?>
            </div>
            <dl class="grid gap-3 text-xs uppercase tracking-wide text-slate-400 sm:grid-cols-2">
                <div>
                    <dt class="text-slate-500">Kontakt</dt>
                    <dd class="text-slate-300"><?= htmlspecialchars($settings['contact_email'] ?? 'info@feroxz.de') ?></dd>
                </div>
                <div>
                    <dt class="text-slate-500">Standort</dt>
                    <dd class="text-slate-300"><?= htmlspecialchars($settings['location'] ?? 'Deutschland') ?></dd>
                </div>
            </dl>
        </div>
        <div class="space-y-4 text-xs text-slate-500">
            <div class="flex items-center gap-2 text-slate-400">
                <span class="text-sm">© <?= date('Y') ?> <?= htmlspecialchars($settings['site_title'] ?? APP_NAME) ?></span>
                <span aria-hidden="true">•</span>
                <span>Alle Rechte vorbehalten.</span>
            </div>
            <p>Design angetrieben durch Tailwind CSS.</p>
        </div>
    </div>
</footer>
<script>
    (function () {
        const mobileToggle = document.querySelector('[data-mobile-nav-toggle]');
        const mobilePanel = document.querySelector('[data-mobile-nav-panel]');
        if (mobileToggle && mobilePanel) {
            mobileToggle.addEventListener('click', () => {
                mobilePanel.classList.toggle('hidden');
                const expanded = mobileToggle.getAttribute('aria-expanded') === 'true';
                mobileToggle.setAttribute('aria-expanded', expanded ? 'false' : 'true');
            });
        }

        document.querySelectorAll('[data-nav-group]').forEach((group) => {
            const trigger = group.querySelector('[data-nav-trigger]');
            const dropdown = group.querySelector('.nav-dropdown');
            if (!trigger || !dropdown) {
                return;
            }
            trigger.setAttribute('aria-haspopup', 'true');
            trigger.setAttribute('aria-expanded', 'false');
            trigger.addEventListener('click', (event) => {
                event.preventDefault();
                const isOpen = dropdown.classList.toggle('open');
                trigger.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
            });
            group.addEventListener('mouseleave', () => {
                dropdown.classList.remove('open');
                trigger.setAttribute('aria-expanded', 'false');
            });
            group.addEventListener('keyup', (event) => {
                if (event.key === 'Escape') {
                    dropdown.classList.remove('open');
                    trigger.setAttribute('aria-expanded', 'false');
                    trigger.focus();
                }
            });
        });
    })();
</script>
<?php if (($currentRoute ?? '') === 'genetics'): ?>
    <script src="<?= asset('genetics.js') ?>"></script>
<?php endif; ?>
<?php if (isset($currentRoute) && str_starts_with($currentRoute, 'admin/')): ?>
    <script src="<?= asset('admin.js') ?>"></script>
<?php endif; ?>
</body>
</html>
