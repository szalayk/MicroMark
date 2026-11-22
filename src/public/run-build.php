<?php

declare(strict_types=1);

/**
 * MicroMark CMS - Build Trigger Script
 * This script allows triggering the index generation process via a web browser.
 * Ideal for shared hosting environments without SSH access.
 */

// Prevent caching of any response (success, form, or error)
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');
header('Expires: 0');

// Load configuration and helpers
require_once '../app/config.php';

/*
|--------------------------------------------------------------------------
| Security Check (POST + hash_equals)
|--------------------------------------------------------------------------
| Expects the build key via POST ('key') so it does not end up in query logs.
*/
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(200);
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>MicroMark Build Trigger</title>
        <style>
            body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; line-height: 1.6; color: #333; max-width: 460px; margin: 60px auto; padding: 20px; background: #f9f9f9; }
            .card { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); border-top: 5px solid #4a90e2; }
            h1 { margin-top: 0; color: #2c3e50; font-size: 22px; }
            label { display: block; margin-bottom: 8px; font-weight: 600; font-size: 14px; }
            input[type="password"] { width: 100%; box-sizing: border-box; padding: 10px 12px; border: 1px solid #dcdcdc; border-radius: 4px; font-size: 15px; margin-bottom: 16px; }
            button { width: 100%; padding: 11px 16px; background: #4a90e2; color: #fff; border: 0; border-radius: 4px; font-size: 15px; font-weight: 600; cursor: pointer; }
            button:hover { background: #3b7bc4; }
        </style>
    </head>
    <body>
        <div class="card">
            <h1>MicroMark Build Trigger</h1>
            <form method="post" action="" autocomplete="off">
                <label for="key">Build Key</label>
                <input type="password" id="key" name="key" required autofocus>
                <button type="submit">Run Index Build</button>
            </form>
        </div>
        <p style="text-align: center; margin-top: 16px;">
            <a href="<?= e((string) SITE_URL) ?>" style="color: #4a90e2; text-decoration: none;">&larr; Back to Website</a>
        </p>
    </body>
    </html>
    <?php
    exit;
}

$submittedKey = $_POST['key'] ?? '';
$expectedKey  = defined('BUILD_KEY') ? (string) BUILD_KEY : '';

if (
    !is_string($submittedKey)
    || $submittedKey === ''
    || $expectedKey === ''
    || !hash_equals($expectedKey, $submittedKey)
) {
    http_response_code(403);
    $authError = 'Please provide a valid build key to run the engine.';
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>403 - Access Denied</title>
        <style>
            body { font-family: sans-serif; text-align: center; padding-top: 50px; background: #f9f9f9; color: #333; }
            .box { max-width: 460px; margin: 0 auto; background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.08); }
            a { display: inline-block; margin-top: 20px; padding: 10px 16px; background: #4a90e2; color: #fff; text-decoration: none; border-radius: 4px; }
        </style>
    </head>
    <body>
        <div class="box">
            <h1 style="color:#e74c3c; margin-top:0;">403 - Access Denied</h1>
            <p><?= e($authError) ?></p>
            <a href="">Try Again</a>
        </div>
    </body>
    </html>
    <?php
    exit;
}

/**
 * Proceed with the build process.
 * build_index.php populates $data with indexed items (or $data['build_error'] on failure).
 */
require_once APP_PATH . '/build_index.php';

if (!empty($data['build_error'])) {
    http_response_code(500);
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>MicroMark Build Error</title>
        <style>
            body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 50px auto; padding: 20px; background: #f9f9f9; }
            .card { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); border-top: 5px solid #e74c3c; }
            .error { background: #f8d7da; color: #721c24; padding: 12px; border-radius: 4px; font-weight: bold; }
        </style>
    </head>
    <body>
        <div class="card">
            <h1>MicroMark Build Failed</h1>
            <div class="error"><?= e((string) $data['build_error']) ?></div>
        </div>
    </body>
    </html>
    <?php
    exit;
}

$pagesCount = count($data['pages'] ?? []);
$postsCount = count($data['posts'] ?? []);
$catsCount  = count($data['categories'] ?? []);
$tagsCount  = count($data['tags'] ?? []);
$indexFile  = realpath(CACHE_PATH . '/index.json') ?: (CACHE_PATH . '/index.json');

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>MicroMark Build Process</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 50px auto; padding: 20px; background: #f9f9f9; }
        .card { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); border-top: 5px solid #4a90e2; }
        h1 { margin-top: 0; color: #2c3e50; font-size: 24px; }
        .status { background: #d4edda; color: #155724; padding: 10px; border-radius: 4px; margin-bottom: 20px; font-weight: bold; text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { text-align: left; padding: 12px; border-bottom: 1px solid #eee; }
        th { color: #7f8c8d; text-transform: uppercase; font-size: 12px; letter-spacing: 1px; }
        .val { font-weight: bold; text-align: right; }
        .footer { margin-top: 20px; font-size: 12px; color: #95a5a6; text-align: center; }
    </style>
</head>
<body>

<div class="card">
    <h1>MicroMark Build Engine</h1>

    <div class="status">
        Indexing completed successfully!
    </div>

    <table>
        <thead>
            <tr>
                <th>Content Type</th>
                <th class="val">Count</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Static Pages</td>
                <td class="val"><?= $pagesCount ?></td>
            </tr>
            <tr>
                <td>Blog Posts</td>
                <td class="val"><?= $postsCount ?></td>
            </tr>
            <tr>
                <td>Categories</td>
                <td class="val"><?= $catsCount ?></td>
            </tr>
            <tr>
                <td>Total Tags</td>
                <td class="val"><?= $tagsCount ?></td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        Cache generated at: <?= e(date('Y-m-d H:i:s')) ?><br>
        File: <?= e($indexFile) ?>
    </div>
</div>

<p style="text-align: center;">
    <a href="<?= e((string) SITE_URL) ?>" style="color: #4a90e2; text-decoration: none;">&larr; Back to Website</a>
</p>

</body>
</html>
