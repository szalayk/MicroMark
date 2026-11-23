<?php include 'partials/header.php'; ?>

<div class="container layout-grid">
    <main class="main-content">
        <?php if (!empty($content)): ?>
            <section>
                <?= $content ?>
            </section>
        <?php endif; ?>
    </main>
    <?php include 'partials/sidebar.php'; ?>
</div>

<?php include 'partials/footer.php'; ?>
