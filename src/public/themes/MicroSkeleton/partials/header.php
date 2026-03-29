<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?> &minus; MicroMark CMS</title>
    <link rel="stylesheet" href="<?php echo THEME_URL; ?>/style.css">
</head>
<body>

<header class="site-header">
    <div class="container">
        <a href="<?php echo SITE_URL; ?>" class="logo"><strong>MicroMark CMS</strong></a>
        <nav>
            <ul>
                <?php foreach ($navigation as $item): ?>
                    <li>
                        <a href="<?php echo SITE_URL . '/' . $item['slug']; ?>">
                            <?php echo $item['title']; ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </nav>
    </div>
</header>
