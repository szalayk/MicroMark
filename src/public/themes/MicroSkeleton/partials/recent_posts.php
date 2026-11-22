<section class="recent-posts-section">
    <div class="container">
        <h3>Continue Reading</h3>
        <div class="recent-posts-grid">
            <?php foreach ($recentPosts as $post): ?>
                <article class="recent-card">
                    <a href="<?= e(SITE_URL.'/'.$post['slug']) ?>">
                        <h4><?= e($post['title'] ?? 'Untitled post') ?></h4>
                        <time><?= e(date('Y. m. d.', $post['timestamp'] ?? time())) ?></time>
                    </a>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
