<?php include 'partials/header.php'; ?>

<main class="container">
    <article class="post-single">
        <header class="post-header">
            <h1><?= e($title) ?></h1>
            
            <div class="post-meta">
                <span>By <?= e($meta['author'] ?? 'Admin') ?></span> | 
                <time><?= e(date('Y. m. d.', $meta['timestamp'])) ?></time> |
                <span>Category: 
                    <a href="<?= e(SITE_URL.'/category/'.urlencode($meta['category'] ?? 'Uncategorized')) ?>">
                        <?= e($meta['category'] ?? 'Uncategorized') ?>
                    </a>
                </span>
            </div>
        </header>

        <?php if (!empty($meta['image'])): ?>
            <div class="post-featured-image">
                <img src="<?= e(SITE_URL.$meta['image']) ?>" alt="<?= e($title) ?>">
            </div>
        <?php endif; ?>

        <section class="post-content">
            <?= $content ?>
        </section>

        <footer class="post-tags">
            <?php if (!empty($meta['tags'])): 
                $tags = explode(',', $meta['tags']); ?>
                <strong>Tags:</strong>
                <?php foreach ($tags as $tag): $tag = trim($tag); ?>
                    <a href="<?= e(SITE_URL.'/tag/'.urlencode($tag)) ?>" class="tag-link">#<?= e($tag) ?></a>
                <?php endforeach; ?>
            <?php endif; ?>
        </footer>
    </article>
</main>

<?php include 'partials/recent_posts.php'; ?>
<?php include 'partials/footer.php'; ?>
