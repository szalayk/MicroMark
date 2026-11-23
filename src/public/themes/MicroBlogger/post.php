<?php include 'partials/header.php'; ?>

<div class="container layout-grid">
    <main class="main-content">
        <article class="post-single">
            <header class="entry-header">
                <h1><?= e($title) ?></h1>
                <p class="entry-meta">
                    <span><?= e($meta['author'] ?? 'Admin') ?></span>
                    <?php if (!empty($meta['timestamp'])): ?>
                        <span aria-hidden="true">&middot;</span>
                        <time datetime="<?= e(date('c', $meta['timestamp'])) ?>">
                            <?= e(date('Y. m. d.', $meta['timestamp'])) ?>
                        </time>
                    <?php endif; ?>
                    <span aria-hidden="true">&middot;</span>
                    <a href="<?= e(SITE_URL.'/category/'.urlencode($meta['category'] ?? 'Uncategorized')) ?>">
                        <?= e($meta['category'] ?? 'Uncategorized') ?>
                    </a>
                </p>
            </header>

            <?php if (!empty($meta['image'])): ?>
                <figure class="featured-media">
                    <img src="<?= e(SITE_URL.$meta['image']) ?>" alt="<?= e($title) ?>">
                </figure>
            <?php endif; ?>

            <section class="prose">
                <?= $content ?>
            </section>

            <?php if (!empty($meta['tags'])): ?>
                <footer class="entry-tags">
                    <?php foreach (explode(',', $meta['tags']) as $tag): $tag = trim($tag); if ($tag === '') continue; ?>
                        <a href="<?= e(SITE_URL.'/tag/'.urlencode($tag)) ?>" class="tag-pill">#<?= e($tag) ?></a>
                    <?php endforeach; ?>
                </footer>
            <?php endif; ?>
        </article>
    </main>

    <?php include 'partials/sidebar.php'; ?>
</div>

<?php include 'partials/footer.php'; ?>
