<div>
    <h3>Recent Posts</h3>
    <ul>
        <?php foreach ($recentPosts as $post): ?>
            <li>
                <a href="<?php echo SITE_URL . '/' . $post['slug']; ?>">
                    <?php echo isset($post['title']) ? $post['title'] : 'Untitled post'; ?>
                </a>
                <span class="meta-date">
                    (<?php echo isset($post['date']) ? $post['date'] : ''; ?>)
                </span>
            </li>
        <?php endforeach; ?>
    </ul>
</div>
