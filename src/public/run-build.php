<?php
/**
 * MicroMark CMS - Build Trigger Script
 * This script allows triggering the index generation process via a web browser.
 * Ideal for shared hosting environments without SSH access.
 */

// Load configuration
require_once '../app/config.php';

/*
|--------------------------------------------------------------------------
| Security Check
|--------------------------------------------------------------------------
| We check if the 'key' GET parameter matches our BUILD_KEY.
| Access this via: yourdomain.com/run-build.php?key=your-password
*/
if (!isset($_GET['key']) || $_GET['key'] !== BUILD_KEY) {
    header('HTTP/1.1 403 Forbidden');
    
    // Error message for the user
    echo "<!DOCTYPE html><html><body style='font-family:sans-serif; text-align:center; padding-top:50px;'>";
    echo "<h1 style='color:#e74c3c;'>403 - Access Denied</h1>";
    echo "<p>Please provide a valid <strong>build key</strong> to run the engine.</p>";
    echo "</body></html>";
    exit;
}

/**
 * Proceed with the build process
 * We capture the build process output if needed, 
 * but since our build_index.php generates a $data array, 
 * we will include it and display the statistics.
 */
require_once APP_PATH . '/build_index.php';

// After including build_index.php, the $data variable contains all the indexed information
$pagesCount = count($data['pages'] ?? []);
$postsCount = count($data['posts'] ?? []);
$catsCount  = count($data['categories'] ?? []);
$tagsCount  = count($data['tags'] ?? []);

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
                <td class="val"><?php echo $pagesCount; ?></td>
            </tr>
            <tr>
                <td>Blog Posts</td>
                <td class="val"><?php echo $postsCount; ?></td>
            </tr>
            <tr>
                <td>Categories</td>
                <td class="val"><?php echo $catsCount; ?></td>
            </tr>
            <tr>
                <td>Total Tags</td>
                <td class="val"><?php echo $tagsCount; ?></td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        Cache generated at: <?php echo date('Y-m-d H:i:s'); ?><br>
        File: <?php echo realpath(CACHE_PATH . '/index.json'); ?>
    </div>
</div>

<p style="text-align: center;">
    <a href="<?php echo SITE_URL; ?>" style="color: #4a90e2; text-decoration: none;">&larr; Back to Website</a>
</p>

</body>
</html>