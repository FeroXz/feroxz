<?php include __DIR__ . '/../partials/header.php'; ?>
<section class="section">
    <div class="section__inner" style="max-width:480px;">
        <article class="card">
            <h2 class="card__title">Login</h2>
            <?php if ($error = flash('error')): ?>
                <div class="alert alert-error" role="alert" aria-live="assertive"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            <form method="post" action="<?= BASE_URL ?>/index.php?route=login" class="form-grid">
                <label class="label">Benutzername
                    <input type="text" name="username" required autofocus class="input">
                </label>
                <label class="label">Passwort
                    <input type="password" name="password" required class="input">
                </label>
                <div class="form-actions">
                    <button type="submit" class="button button--primary">Anmelden</button>
                </div>
            </form>
        </article>
    </div>
</section>
<?php include __DIR__ . '/../partials/footer.php'; ?>
