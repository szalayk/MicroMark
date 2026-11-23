<?php include 'partials/header.php'; ?>

<div class="container layout-grid">
    <main class="main-content">
        <?php if (!empty($content)): ?>
            <section class="home-intro">
                <?= $content ?>
            </section>
        <?php endif; ?>

        <section class="home-posts" aria-labelledby="home-posts-heading">
            <h2 id="home-posts-heading">Recent posts</h2>
            <?php if (!empty($recentPosts)): ?>
                <?php foreach ($recentPosts as $post): ?>
                    <article class="post-entry">
                        <?php if (!empty($post['image'])): ?>
                            <a href="<?= e(SITE_URL.'/'.$post['slug']) ?>" class="thumb-link">
                                <img src="<?= e(SITE_URL.$post['image']) ?>" alt="<?= e($post['title'] ?? '') ?>" loading="lazy">
                            </a>
                        <?php endif; ?>
                        <div class="entry-body">
                            <h3>
                                <a href="<?= e(SITE_URL.'/'.$post['slug']) ?>">
                                    <?= e($post['title'] ?? 'Untitled post') ?>
                                </a>
                            </h3>
                            <?php if (!empty($post['timestamp']) || !empty($post['date'])): ?>
                                <time class="meta-date">
                                    <?= e(!empty($post['timestamp']) ? date('Y. m. d.', $post['timestamp']) : $post['date']) ?>
                                </time>
                            <?php endif; ?>
                            <?php if (!empty($post['description'])): ?>
                                <p class="entry-excerpt"><?= e($post['description']) ?></p>
                            <?php endif; ?>
                        </div>
                    </article>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="muted">There are no entries yet.</p>
            <?php endif; ?>
        </section>
    </main>

    <?php include 'partials/sidebar.php'; ?>
</div>

<?php include 'partials/footer.php'; ?>
