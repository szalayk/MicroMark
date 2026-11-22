<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title) ?> &minus; MicroMark CMS</title>
    <link rel="stylesheet" href="<?= e(THEME_URL) ?>/style.css">
</head>
<body>

<header class="site-header">
    <div class="container">
        <a href="<?= e(SITE_URL) ?>" class="logo"><strong>MicroMark CMS</strong></a>
        <nav>
            <ul>
                <?php foreach ($navigation as $item): ?>
                    <li>
                        <a href="<?= e(SITE_URL.'/'.$item['slug']) ?>">
                            <?= e($item['title']) ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </nav>
    </div>
</header>
