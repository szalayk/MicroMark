<aside class="sidebar" aria-label="Recent posts">
    <h2>Recent posts</h2>
    <?php if (!empty($recentPosts)): ?>
        <ul class="recent-list">
            <?php foreach ($recentPosts as $recentPost): ?>
                <li>
                    <a href="<?= e(SITE_URL.'/'.$recentPost['slug']) ?>">
                        <?= e($recentPost['title'] ?? 'Untitled post') ?>
                    </a>
                    <?php if (!empty($recentPost['timestamp'])): ?>
                        <time datetime="<?= e(date('c', $recentPost['timestamp'])) ?>">
                            <?= e(date('Y. m. d.', $recentPost['timestamp'])) ?>
                        </time>
                    <?php elseif (!empty($recentPost['date'])): ?>
                        <time><?= e($recentPost['date']) ?></time>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p class="muted">There are no entries yet.</p>
    <?php endif; ?>
</aside>
