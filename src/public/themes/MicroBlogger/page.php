<?php include 'partials/header.php'; ?>

<div class="container layout-grid">
    <main class="main-content">
        <article class="page-single">
            <header class="entry-header">
                <h1><?= e($title) ?></h1>
            </header>

            <?php if (!empty($meta['image'])): ?>
                <figure class="featured-media">
                    <img src="<?= e(SITE_URL.$meta['image']) ?>" alt="<?= e($title) ?>">
                </figure>
            <?php endif; ?>

            <section class="prose">
                <?= $content ?>
            </section>
        </article>
    </main>

    <?php include 'partials/sidebar.php'; ?>
</div>

<?php include 'partials/footer.php'; ?>
