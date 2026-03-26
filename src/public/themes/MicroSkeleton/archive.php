<?php include THEME_PATH . '/partials/header.php'; ?>

<main>
    <h1><?php echo $title; ?></h1>

    <?php if (empty($listPosts)): ?>
        <p>No entries found in this category/tag.</p>
    <?php else: ?>
        <?php foreach ($listPosts as $post): ?>
            <div class="post-item">
                <h2><a href="<?php echo SITE_URL . '/' . $post['slug']; ?>"><?php echo isset($post['title']) ? $post['title'] : 'Untitled'; ?></a></h2>
                <div class="meta">
                    Date: <?php echo isset($post['date']) ? $post['date'] : ''; ?> |
                    Category: <a href="<?php echo SITE_URL . '/category/' . urlencode(isset($post['category']) ? trim($post['category']) : 'Uncategorized'); ?>">
                        <?php echo isset($post['category']) ? $post['category'] : 'Uncategorized'; ?>
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</main>

<?php include THEME_PATH . '/partials/footer.php'; ?>