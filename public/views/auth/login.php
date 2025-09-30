<?php include __DIR__ . '/../partials/header.php'; ?>
<div class="grid" style="max-width:480px;margin:0 auto;">
    <div class="card">
        <h2>Login</h2>
        <?php if (!empty($flashError)): ?>
            <div class="alert alert-error"><?= htmlspecialchars($flashError) ?></div>
        <?php endif; ?>
        <?php if (!empty($flashSuccess)): ?>
            <div class="alert alert-success"><?= htmlspecialchars($flashSuccess) ?></div>
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
