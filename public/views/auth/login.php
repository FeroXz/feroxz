<?php include __DIR__ . '/../partials/header.php'; ?>
<section class="mx-auto flex w-full max-w-md flex-col gap-6 px-4 sm:px-6 lg:px-8">
    <div class="rounded-3xl border border-white/5 bg-night-900/70 p-8 shadow-lg shadow-black/30">
        <h2 class="text-2xl font-semibold text-white">Login</h2>
        <?php if ($error = flash('error')): ?>
            <div class="mt-4 rounded-2xl border border-rose-500/40 bg-rose-500/10 px-4 py-3 text-sm text-rose-200" role="alert" aria-live="assertive"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <?php $inputClasses = 'mt-1 block w-full rounded-xl border border-white/10 bg-night-900/60 px-3 py-2 text-slate-100 shadow-inner shadow-black/40 focus:border-brand-400 focus:outline-none focus:ring focus:ring-brand-500/40'; ?>
        <form method="post" action="<?= BASE_URL ?>/index.php?route=login" class="mt-6 grid gap-4 text-sm text-slate-200">
            <label class="space-y-1">
                <span class="font-medium text-slate-200">Benutzername</span>
                <input type="text" name="username" required autofocus class="<?= $inputClasses ?>">
            </label>
            <label class="space-y-1">
                <span class="font-medium text-slate-200">Passwort</span>
                <input type="password" name="password" required class="<?= $inputClasses ?>">
            </label>
            <button type="submit" class="mt-4 inline-flex items-center justify-center rounded-full border border-brand-400/60 bg-brand-500/20 px-4 py-2 text-sm font-semibold text-brand-100 transition hover:border-brand-300 hover:bg-brand-500/30">Anmelden</button>
        </form>
    </div>
</section>
<?php include __DIR__ . '/../partials/footer.php'; ?>
