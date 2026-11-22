<?php include 'partials/header.php'; ?>

<main class="container">
    <header class="archive-header">
        <h1><?= e($title) ?></h1>
        <p>Found <?= e(count($content)) ?> posts in this section.</p>
    </header>

    <section class="post-list">
        <?php if (!empty($content)): ?>
            <?php foreach ($content as $post): ?>
                <article class="post-preview">
                    <h2>
                        <a href="<?= e(SITE_URL.'/'.$post['slug']) ?>"><?= e($post['title']) ?></a>
                    </h2>
                    <div class="post-excerpt">
                        <time><?= e(date('Y. m. d.', $post['timestamp'])) ?></time>
                        <p><?= e($post['description'] ?? 'Read more about this post...') ?></p>
                    </div>
                </article>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No posts found in this archive.</p>
        <?php endif; ?>
    </section>
</main>

<?php include 'partials/footer.php'; ?>
