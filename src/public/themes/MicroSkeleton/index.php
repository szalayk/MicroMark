<?php include THEME_PATH . '/partials/header.php'; ?>

<main>
    <?php if(isset($meta['date'])): ?>
        <div class="meta">Published: <?php echo $meta['date']; ?> | By: <?php echo isset($meta['author']) ? $meta['author'] : 'Unknown'; ?></div>
    <?php endif; ?>

    <?php echo $content; ?>
</main>

<aside>
    <?php include THEME_PATH . '/partials/recent_posts.php'; ?>
</aside>

<?php include THEME_PATH . '/partials/footer.php'; ?>