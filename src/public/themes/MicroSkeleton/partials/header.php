<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?> &minus; MicroMark CMS</title>
    <style>
        body { font-family: sans-serif; line-height: 1.6; max-width: 800px; margin: 0 auto; padding: 20px; }
        header, footer { background: #f4f4f4; padding: 10px; margin-bottom: 20px; }
        nav a { margin-right: 15px; text-decoration: none; color: #333; }
        .post-item { margin-bottom: 20px; border-bottom: 1px solid #eee; padding-bottom: 10px; }
        .meta { color: #888; font-size: 0.9em; }
        .tag { background: #eee; padding: 2px 6px; border-radius: 4px; font-size: 0.8em; text-decoration: none; color: #333; }
    </style>
</head>
<body>

<header>
    <nav>
        <a href="<?php echo SITE_URL; ?>"><strong>MicroMark CMS</strong></a>
        <?php foreach ($navigation as $item): ?>
        <a href="<?php echo SITE_URL . '/' . $item['slug']; ?>">
            <?php echo $item['title']; ?>
        </a>
        <?php endforeach; ?>
    </nav>
</header>
