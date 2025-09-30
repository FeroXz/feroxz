<section class="card">
    <h2>Admin Login</h2>
    <p>Standardzugang: <strong>admin</strong> / <strong>12345678</strong> (ändere das Passwort nach dem ersten Login im Datenbank-Backend).</p>
    <form method="post" action="<?= url('login') ?>">
        <label for="username">Benutzername</label>
        <input id="username" name="username" type="text" required>

        <label for="password">Passwort</label>
        <input id="password" name="password" type="password" required>

        <button class="button" type="submit">Anmelden</button>
    </form>
</section>
