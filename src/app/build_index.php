<?php
/**
 * MicroMark CMS - Index Builder
 * Recursively scans the content directory and generates a cached index.json file.
 */

require_once __DIR__ . '/config.php';
require_once CORE_PATH . '/FileHandler.php';

use App\Core\FileHandler;

// Ensure the cache directory exists
if (!is_dir(CACHE_PATH)) {
    mkdir(CACHE_PATH, 0775, true);
}

$data = [
    'pages' => [],
    'posts' => []
];

// --- PROCESS POSTS ---
$postFiles = glob(CONTENT_PATH . '/posts/*.md');
foreach ($postFiles as $file) {
    $parsed = FileHandler::parseFile($file);
    $slug = basename($file, '.md'); // Filename without extension serves as the slug

    // Default date if missing from FrontMatter
    $dateStr = isset($parsed['meta']['date']) ? $parsed['meta']['date'] : '1970-01-01';

    // Store metadata and file path. Content is excluded from JSON to keep it lightweight.
    $data['posts'][$slug] = array_merge($parsed['meta'], [
        'file_path' => $file,
        'slug'      => $slug,
        'type'      => 'post',
        'timestamp' => strtotime($dateStr) // UNIX timestamp for easy sorting
    ]);
}

// Sort posts chronologically (newest first)
uasort($data['posts'], function($a, $b) {
    return $b['timestamp'] <=> $a['timestamp'];
});

// --- COLLECT CATEGORIES AND TAGS ---
$data['categories'] = [];
$data['tags'] = [];

// Since $data['posts'] is already sorted, category/tag lists will also be chronological.
foreach ($data['posts'] as $slug => $post) {
    
    // Process Category (default to "Uncategorized")
    $cat = isset($post['category']) ? trim($post['category']) : 'Uncategorized';
    if (!isset($data['categories'][$cat])) {
        $data['categories'][$cat] = [];
    }
    $data['categories'][$cat][] = $slug;

    // Process Tags (comma-separated list)
    if (isset($post['tags']) && !empty($post['tags'])) {
        $tags = array_map('trim', explode(',', $post['tags']));
        foreach ($tags as $tag) {
            if (!isset($data['tags'][$tag])) {
                $data['tags'][$tag] = [];
            }
            $data['tags'][$tag][] = $slug;
        }
    }
}

// --- PROCESS PAGES (Recursive) ---
$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(CONTENT_PATH . '/pages'));
foreach ($iterator as $file) {
    if ($file->isFile() && $file->getExtension() === 'md') {
        $parsed = FileHandler::parseFile($file->getPathname());
        $slug = $file->getBasename('.md'); 
        
        // Special case: index.md or home.md -> root slug (empty)
        if ($slug === 'index' || $slug === 'home') {
            $slug = ''; 
        }

        $data['pages'][$slug] = array_merge($parsed['meta'], [
            'file_path' => $file->getPathname(),
            'slug' => $slug,
            'type' => 'page'
        ]);
    }
}

// --- GENERATE MENU ---
$menu = [];
foreach ($data['pages'] as $slug => $pageInfo) {
    // If 'title' exists and 'menu_show' is not explicitly 'false'
    if (isset($pageInfo['title']) && (!isset($pageInfo['menu_show']) || $pageInfo['menu_show'] !== 'false')) {
        $menu[] = [
            'title' => $pageInfo['title'],
            'slug' => $slug,
            'order' => isset($pageInfo['menu_order']) ? (int)$pageInfo['menu_order'] : 99
        ];
    }
}

// Sort menu based on 'order' key
usort($menu, function($a, $b) {
    return $a['order'] <=> $b['order'];
});

$data['menu'] = $menu;

// Save to JSON
$jsonPath = CACHE_PATH . '/index.json';
file_put_contents($jsonPath, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

// Only echo the success message if running from the Command Line Interface (CLI)
if (php_sapi_name() === 'cli') {
    echo "index.json successfully generated at: $jsonPath\n";
}
