<?php include 'partials/header.php'; ?>

<main class="container">
    <section class="home-content">
        <?php echo $content; ?>
    </section>

    <hr>

    <section class="home-post-list container">
        <h3>Recent Posts</h3>
        <?php foreach ($recentPosts as $post): ?>
            <article class="post-preview-horizontal">
                <?php if (!empty($post['image'])): ?>
                    <div class="post-preview-image">
                        <a href="<?php echo SITE_URL . '/' . $post['slug']; ?>">
                            <img src="<?php echo SITE_URL . $post['image']; ?>" alt="<?php echo $post['title']; ?>">
                        </a>
                    </div>
                <?php endif; ?>
                
                <div class="post-preview-content">
                    <h2>
                        <a href="<?php echo SITE_URL . '/' . $post['slug']; ?>">
                            <?php echo $post['title'] ?? 'Untitled post'; ?>
                        </a>
                    </h2>
                    <div class="post-meta">
                        <time><?php echo $post['date'] ?? ''; ?></time>
                    </div>
                    <?php if (!empty($post['description'])): ?>
                        <p class="post-excerpt-text">
                            <?php echo $post['description']; ?>
                        </p>
                    <?php endif; ?>
                </div>
            </article>
        <?php endforeach; ?>
    </section>
</main>

<?php include 'partials/footer.php'; ?>