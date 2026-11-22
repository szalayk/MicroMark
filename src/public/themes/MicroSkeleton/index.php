<?php include 'partials/header.php'; ?>

<main class="container">
    <section class="home-content">
        <?= $content ?>
    </section>

    <hr>

    <section class="home-post-list container">
        <h3>Recent Posts</h3>
        <?php foreach ($recentPosts as $post): ?>
            <article class="post-preview-horizontal">
                <?php if (!empty($post['image'])): ?>
                    <div class="post-preview-image">
                        <a href="<?= e(SITE_URL.'/'.$post['slug']) ?>">
                            <img src="<?= e(SITE_URL.$post['image']) ?>" alt="<?= e($post['title']) ?>">
                        </a>
                    </div>
                <?php endif; ?>
                
                <div class="post-preview-content">
                    <h2>
                        <a href="<?= e(SITE_URL.'/'.$post['slug']) ?>">
                            <?= e($post['title'] ?? 'Untitled post') ?>
                        </a>
                    </h2>
                    <div class="post-meta">
                        <time><?= e($post['date'] ?? '') ?></time>
                    </div>
                    <?php if (!empty($post['description'])): ?>
                        <p class="post-excerpt-text">
                            <?= e($post['description']) ?>
                        </p>
                    <?php endif; ?>
                </div>
            </article>
        <?php endforeach; ?>
    </section>
</main>

<?php include 'partials/footer.php'; ?>
