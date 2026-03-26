<?php

namespace App\Core;

use Parsedown;

/**
 * Class Cms
 * The core engine of the MicroMark CMS.
 * Handles requests, index data loading, and rendering.
 */
class Cms
{
    /** @var array Data loaded from the index cache. */
    private $indexData;
    
    /** @var Parsedown Markdown parser instance. */
    private $parsedown;

    /**
     * Cms constructor.
     * Loads the cached index data and initializes the markdown parser.
     */
    public function __construct()
    {
        $jsonPath = BASE_PATH . '/cache/index.json';
        if (!file_exists($jsonPath)) {
            die("Error: cache/index.json not found. Please run build_index.php!");
        }
        $this->indexData = json_decode(file_get_contents($jsonPath), true);
        $this->parsedown = new Parsedown();
    }

    /**
     * Main request handler.
     * Dispatches the request based on URI segments.
     *
     * @param array $segments URI segments.
     */
    public function handleRequest($segments)
    {
        // First segment of the URI (e.g., "first-blog-post" or "contact")
        // If empty, it's the home page.
        $slug = isset($segments[0]) && !empty($segments[0]) ? $segments[0] : '';
        $subSlug = isset($segments[1]) ? urldecode($segments[1]) : '';

        // Handle categories
        if ($slug === 'category' && $subSlug !== '') {
            if (isset($this->indexData['categories'][$subSlug])) {
                $posts = $this->getPostsBySlugs($this->indexData['categories'][$subSlug]);
                $this->renderList('Category: ' . $subSlug, $posts);
            } else {
                $this->error404();
            }
            return;
        }

        // Handle tags
        if ($slug === 'tag' && $subSlug !== '') {
            if (isset($this->indexData['tags'][$subSlug])) {
                $posts = $this->getPostsBySlugs($this->indexData['tags'][$subSlug]);
                $this->renderList('Tag: ' . $subSlug, $posts);
            } else {
                $this->error404();
            }
            return;
        }

        // Check if it's a PAGE
        if (array_key_exists($slug, $this->indexData['pages'])) {
            $this->render($this->indexData['pages'][$slug]);
            return;
        }

        // If not a page, check if it's a POST
        if (array_key_exists($slug, $this->indexData['posts'])) {
            $this->render($this->indexData['posts'][$slug]);
            return;
        }

        // Nothing found: 404
        $this->error404();
    }

    /**
     * Retrieve the most recent posts.
     *
     * @param int $limit Number of posts to retrieve.
     * @return array
     */
    public function getRecentPosts($limit = 5)
    {
        // Since the JSON is already sorted during build, we just slice it.
        return array_slice($this->indexData['posts'], 0, $limit, true);
    }

    /**
     * Helper to retrieve post data based on an array of slugs.
     *
     * @param array $slugArray
     * @return array
     */
    private function getPostsBySlugs($slugArray)
    {
        $posts = [];
        foreach ($slugArray as $slug) {
            if (isset($this->indexData['posts'][$slug])) {
                $posts[] = $this->indexData['posts'][$slug];
            }
        }
        return $posts;
    }

    /**
     * Render a list of posts (e.g., category or tag results).
     *
     * @param string $listTitle The title for the list.
     * @param array $listPosts Array of post data.
     */
    private function renderList($listTitle, $listPosts)
    {
        $title = $listTitle;
        $navigation = $this->indexData['menu'] ?? [];
        $recentPosts = $this->getRecentPosts(5);
        
        // Load the specialized list theme file if it exists
        if (file_exists(THEME_PATH . '/archive.php')) {
            require_once THEME_PATH . '/archive.php';
        } else {
            // Fallback to layout.php if list.php is missing
            $content = "<ul>";
            foreach($listPosts as $p) {
                $content .= "<li><a href='".SITE_URL."/{$p['slug']}'>{$p['title']}</a></li>";
            }
            $content .= "</ul>";
            require_once THEME_PATH . '/index.php';
        }
    }

    /**
     * Render a specific page or post.
     *
     * @param array $nodeData Metadata for the page/post.
     */
    private function render($nodeData)
    {
        // Parse the physical file content
        $parsed = FileHandler::parseFile($nodeData['file_path']);
        
        // Prepare variables for the view
        $title = isset($nodeData['title']) ? $nodeData['title'] : 'Untitled';
        $content = $this->parsedown->text($parsed['content']);
        $meta = $nodeData; // All other metadata (date, author, etc.)

        // Pass menu and recent posts to the layout
        $navigation = $this->indexData['menu'] ?? [];
        $recentPosts = $this->getRecentPosts(5);

        // Load theme layout
        // $content, $title, and $meta will be available in index.php
        if (file_exists(THEME_PATH . '/index.php')) {
            require_once THEME_PATH . '/index.php';
        } else {
            echo "Error: Theme layout file (index.php) not found.";
        }
    }

    /**
     * Send 404 response and render the error page.
     */
    private function error404()
    {
        http_response_code(404);
        $title = "404 Not Found";
        $content = "<h1>Page not found</h1><p>Sorry, but the content you are looking for does not exist.</p>";

        // Pass menu and recent posts to the layout
        $navigation = $this->indexData['menu'] ?? [];
        $recentPosts = $this->getRecentPosts(5);

        if (file_exists(THEME_PATH . '/index.php')) {
            require_once THEME_PATH . '/index.php';
        } else {
            echo "404 - Not Found";
        }
    }
}