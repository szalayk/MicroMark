<?php

declare(strict_types=1);

/**
 * MicroMark CMS - Index Builder
 * Scans the content directory and generates a cached index.json file.
 */

require_once __DIR__ . '/config.php';
require_once CORE_PATH . '/FileHandler.php';

use App\Core\FileHandler;

$data = [
    'pages' => [],
    'posts' => [],
];

// Ensure the cache directory exists
if (!is_dir(CACHE_PATH) && !mkdir(CACHE_PATH, 0775, true) && !is_dir(CACHE_PATH)) {
    $lastErr = error_get_last();
    $data['build_error'] = 'Failed to create cache directory (' . CACHE_PATH . ')'
        . (isset($lastErr['message']) ? ': ' . $lastErr['message'] : '');
}

// --- PROCESS POSTS ---
$postsDir = CONTENT_PATH . '/posts';
$postFiles = is_dir($postsDir) ? (glob($postsDir . '/*.md') ?: []) : [];

foreach ($postFiles as $file) {
    if (!is_file($file) || is_link($file)) {
        continue;
    }

    $relativePath = 'posts/' . basename($file);
    $parsed = FileHandler::parseFile($relativePath);
    if ($parsed === null) {
        continue;
    }

    $slug = basename($file, '.md');
    $dateStr = $parsed['meta']['date'] ?? '1970-01-01';
    $timestamp = strtotime($dateStr);

    // Store metadata and relative file path. Content is excluded from JSON to keep it lightweight.
    $entry = $parsed['meta'];
    $entry['file_path'] = $relativePath;
    $entry['slug']      = $slug;
    $entry['type']      = 'post';
    $entry['timestamp'] = $timestamp !== false ? $timestamp : 0;

    $data['posts'][$slug] = $entry;
}

// Sort posts chronologically (newest first)
uasort($data['posts'], static function (array $a, array $b): int {
    return $b['timestamp'] <=> $a['timestamp'];
});

// --- COLLECT CATEGORIES AND TAGS ---
$data['categories'] = [];
$data['tags'] = [];

// Since $data['posts'] is already sorted, category/tag lists will also be chronological.
foreach ($data['posts'] as $slug => $post) {
    // Process Category (default to "Uncategorized")
    $cat = isset($post['category']) && trim($post['category']) !== ''
        ? trim($post['category'])
        : 'Uncategorized';

    if (!isset($data['categories'][$cat])) {
        $data['categories'][$cat] = [];
    }
    $data['categories'][$cat][] = $slug;

    // Process Tags (comma-separated list)
    if (!empty($post['tags'])) {
        $tags = array_filter(
            array_map('trim', explode(',', $post['tags'])),
            static fn(string $t): bool => $t !== ''
        );
        foreach ($tags as $tag) {
            if (!isset($data['tags'][$tag])) {
                $data['tags'][$tag] = [];
            }
            $data['tags'][$tag][] = $slug;
        }
    }
}

// --- PROCESS PAGES (Recursive) ---
$pagesDir = CONTENT_PATH . '/pages';
$realPagesDir = realpath($pagesDir);

if ($realPagesDir !== false && is_dir($realPagesDir)) {
    $directoryIterator = new RecursiveDirectoryIterator(
        $realPagesDir,
        FilesystemIterator::SKIP_DOTS
    );
    $iterator = new RecursiveIteratorIterator($directoryIterator);

    foreach ($iterator as $file) {
        if (!$file->isFile() || $file->isLink() || strtolower($file->getExtension()) !== 'md') {
            continue;
        }

        $realFile = $file->getRealPath();
        if ($realFile === false) {
            continue;
        }

        // Build relative path inside CONTENT_PATH/pages (e.g. "about.md" or "docs/intro.md")
        $subPath = ltrim(substr($realFile, strlen($realPagesDir)), DIRECTORY_SEPARATOR);
        $subPathNormalized = str_replace(DIRECTORY_SEPARATOR, '/', $subPath);
        $relativePath = 'pages/' . $subPathNormalized;

        $parsed = FileHandler::parseFile($relativePath);
        if ($parsed === null) {
            continue;
        }

        $slug = substr($subPathNormalized, 0, -3); // strip ".md"

        // Special case: top-level index.md or home.md -> root slug (empty)
        if ($slug === 'index' || $slug === 'home') {
            $slug = '';
        }

        $entry = $parsed['meta'];
        $entry['file_path'] = $relativePath;
        $entry['slug']      = $slug;
        $entry['type']      = 'page';

        $data['pages'][$slug] = $entry;
    }
}

// --- GENERATE MENU ---
$menu = [];
foreach ($data['pages'] as $slug => $pageInfo) {
    // If 'title' exists and 'menu_show' is not explicitly 'false'
    if (isset($pageInfo['title']) && (!isset($pageInfo['menu_show']) || strtolower((string) $pageInfo['menu_show']) !== 'false')) {
        $menu[] = [
            'title' => $pageInfo['title'],
            'slug'  => $slug,
            'order' => isset($pageInfo['menu_order']) ? (int) $pageInfo['menu_order'] : 99,
        ];
    }
}

// Sort menu based on 'order' key
usort($menu, static function (array $a, array $b): int {
    return $a['order'] <=> $b['order'];
});

$data['menu'] = $menu;

// --- SAVE TO JSON ---
if (empty($data['build_error'])) {
    $jsonPath = CACHE_PATH . '/index.json';
    $jsonContent = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

    if ($jsonContent === false) {
        $data['build_error'] = 'Failed to encode index data to JSON: ' . json_last_error_msg();
    } elseif (!is_writable(CACHE_PATH)) {
        $data['build_error'] = 'Cache directory is not writable: ' . CACHE_PATH;
    } else {
        $written = file_put_contents($jsonPath, $jsonContent, LOCK_EX);
        if ($written === false) {
            $written = file_put_contents($jsonPath, $jsonContent);
        }

        if ($written === false) {
            $data['build_error'] = 'Failed to write cache/index.json (check directory and file permissions).';
        }
    }
}

if (!empty($data['build_error'])) {
    if (php_sapi_name() === 'cli') {
        fwrite(STDERR, "Error: {$data['build_error']}\n");
        exit(1);
    }
} elseif (php_sapi_name() === 'cli') {
    echo "index.json successfully generated at: " . CACHE_PATH . "/index.json\n";
}

