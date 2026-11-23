<?php include 'partials/header.php'; ?>

<?php
if (count($content) <= 1) {
	$text = '&nbsp;post on this topic';
} else {
	$text = '&nbsp;posts on this topic';
}
?>

<div class="container layout-grid">
    <main class="main-content">
        <header class="entry-header">
            <h1><?= e($title) ?></h1>
            <p class="entry-meta"><?= e(count($content)).$text ?></p>
        </header>

        <?php if (!empty($content)): ?>
            <div class="archive-list">
                <?php foreach ($content as $post): ?>
                    <article class="post-entry">
                        <div class="entry-body">
                            <h2>
                                <a href="<?= e(SITE_URL.'/'.$post['slug']) ?>"><?= e($post['title']) ?></a>
                            </h2>
                            <?php if (!empty($post['timestamp'])): ?>
                                <time class="meta-date" datetime="<?= e(date('c', $post['timestamp'])) ?>">
                                    <?= e(date('Y. m. d.', $post['timestamp'])) ?>
                                </time>
                            <?php endif; ?>
                            <p class="entry-excerpt"><?= e($post['description'] ?? '') ?></p>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="muted">Nincs találat ebben az archívumban.</p>
        <?php endif; ?>
    </main>

    <?php include 'partials/sidebar.php'; ?>
</div>

<?php include 'partials/footer.php'; ?>
