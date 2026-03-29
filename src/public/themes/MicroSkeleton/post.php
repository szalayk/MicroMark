<?php include 'partials/header.php'; ?>

<main class="container">
    <article class="post-single">
        <header class="post-header">
            <h1><?php echo $title; ?></h1>
            
            <div class="post-meta">
                <span>By <?php echo $meta['author'] ?? 'Admin'; ?></span> | 
                <time><?php echo date('Y. m. d.', $meta['timestamp']); ?></time> |
                <span>Category: 
                    <a href="<?php echo SITE_URL . '/category/' . urlencode($meta['category'] ?? 'Uncategorized'); ?>">
                        <?php echo $meta['category'] ?? 'Uncategorized'; ?>
                    </a>
                </span>
            </div>
        </header>

        <?php if (!empty($meta['image'])): ?>
            <div class="post-featured-image">
                <img src="<?php echo SITE_URL . $meta['image']; ?>" alt="<?php echo $title; ?>">
            </div>
        <?php endif; ?>

        <section class="post-content">
            <?php echo $content; ?>
        </section>

        <footer class="post-tags">
            <?php if (!empty($meta['tags'])): 
                $tags = explode(',', $meta['tags']); ?>
                <strong>Tags:</strong>
                <?php foreach ($tags as $tag): $tag = trim($tag); ?>
                    <a href="<?php echo SITE_URL . '/tag/' . urlencode($tag); ?>" class="tag-link">#<?php echo $tag; ?></a>
                <?php endforeach; ?>
            <?php endif; ?>
        </footer>
    </article>
</main>

<?php include 'partials/recent_posts.php'; ?>
<?php include 'partials/footer.php'; ?>