<section class="recent-posts-section">
    <div class="container">
        <h3>Continue Reading</h3>
        <div class="recent-posts-grid">
            <?php foreach ($recentPosts as $post): ?>
                <article class="recent-card">
                    <a href="<?php echo SITE_URL . '/' . $post['slug']; ?>">
                        <h4><?php echo $post['title'] ?? 'Untitled post'; ?></h4>
                        <time><?php echo date('Y. m. d.', $post['timestamp'] ?? time()); ?></time>
                    </a>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>