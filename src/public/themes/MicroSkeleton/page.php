<?php include 'partials/header.php'; ?>

<main class="container">
    <article class="page-content">
        <h1><?= e($title) ?></h1>

        <?php if(isset($meta['image'])): ?>
            <div class="post-featured-image">
                <img src="<?= e(SITE_URL.$meta['image']) ?>">
            </div>
        <?php endif; ?>

        <section class="content">
            <?= $content ?>
        </section>
    </article>
</main>

<?php include 'partials/recent_posts.php'; ?>
<?php include 'partials/footer.php'; ?>
