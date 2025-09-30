    </div>
</main>
<footer class="footer">
    <div class="wrapper">
        <?= nl2br(htmlspecialchars($settings['footer_text'] ?? '')) ?>
    </div>
</footer>
<?php if (isset($currentRoute) && str_starts_with($currentRoute, 'admin/')): ?>
    <script src="<?= asset('admin.js') ?>"></script>
<?php endif; ?>
</body>
</html>
