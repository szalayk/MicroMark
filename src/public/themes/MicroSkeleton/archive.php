<?php include 'partials/header.php'; ?>

<main class="container">
    <header class="archive-header">
        <h1><?php echo $title;?></h1>
        <p>Found <?php echo count($content); ?> posts in this section.</p>
    </header>

    <section class="post-list">
        <?php if (!empty($content)): ?>
            <?php foreach ($content as $post): ?>
                <article class="post-preview">
                    <h2>
                        <a href="<?php echo SITE_URL . '/' . $post['slug']; ?>">
                            <?php echo $post['title']; ?>
                        </a>
                    </h2>
                    <div class="post-excerpt">
                        <time><?php echo date('Y. m. d.', $post['timestamp']); ?></time>
                        <p><?php echo $post['description'] ?? 'Read more about this post...'; ?></p>
                    </div>
                </article>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No posts found in this archive.</p>
        <?php endif; ?>
    </section>
</main>

<?php include 'partials/footer.php'; ?>