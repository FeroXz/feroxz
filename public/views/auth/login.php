<?php include __DIR__ . '/../partials/header.php'; ?>
<div class="grid" style="max-width:480px;margin:0 auto;">
    <div class="card">
        <h2>Login</h2>
        <?php if ($error = flash('error')): ?>
            <div class="alert alert-error" role="alert" aria-live="assertive"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <form method="post" action="<?= BASE_URL ?>/index.php?route=login">
            <label>Benutzername
                <input type="text" name="username" required autofocus>
            </label>
            <label>Passwort
                <input type="password" name="password" required>
            </label>
            <button type="submit">Anmelden</button>
        </form>
    </div>
</div>
<?php include __DIR__ . '/../partials/footer.php'; ?>
