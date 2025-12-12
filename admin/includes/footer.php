            </div><!-- .admin-content -->
        </main>
    </div>

    <script src="<?= SITE_URL ?>/admin/assets/js/admin.js"></script>
    <?php if (isset($extraScripts)): ?>
        <?php foreach ($extraScripts as $script): ?>
        <script src="<?= $script ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html>
