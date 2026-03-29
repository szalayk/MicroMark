<?php

namespace App\Core;

use Parsedown;

class Cms
{
    private $indexData;
    private $parsedown;

    public function __construct()
    {
        $jsonPath = BASE_PATH . '/cache/index.json';
        if (!file_exists($jsonPath)) {
            die("Error: cache/index.json not found. Please run build_index.php!");
        }
        $this->indexData = json_decode(file_get_contents($jsonPath), true);
        $this->parsedown = new Parsedown();
    }

    public function handleRequest($segments)
    {
        $slug = isset($segments[0]) && !empty($segments[0]) ? $segments[0] : '';
        $subSlug = isset($segments[1]) ? urldecode($segments[1]) : '';

        // Handle categories and tags using archive.php
        if (($slug === 'category' || $slug === 'tag') && $subSlug !== '') {
            $taxonomy = ($slug === 'category') ? 'categories' : 'tags';
            if (isset($this->indexData[$taxonomy][$subSlug])) {
                $posts = $this->getPostsBySlugs($this->indexData[$taxonomy][$subSlug]);
                $this->renderList(($slug === 'category' ? 'Category: ' : 'Tag: ') . $subSlug, $posts);
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

        // Check if it's a POST
        if (array_key_exists($slug, $this->indexData['posts'])) {
            $this->render($this->indexData['posts'][$slug]);
            return;
        }

        $this->error404();
    }

    /**
     * Renders a single page or post using the template hierarchy.
     */
    private function render($nodeData)
    {
        $parsed = FileHandler::parseFile($nodeData['file_path']);
        
        $title = $nodeData['title'] ?? 'Untitled';
        $content = $this->parsedown->text($parsed['content']);
        $meta = $nodeData;
        $navigation = $this->indexData['menu'] ?? [];
        $recentPosts = $this->getRecentPosts(5);

        // --- Template Hierarchy Logic ---
        $template = 'index.php'; // Default fallback

        if ($nodeData['slug'] === '') {
            // Home page uses index.php as requested
            $template = 'index.php';
        } elseif ($nodeData['type'] === 'page') {
            // Try page.php for static pages
            $template = file_exists(THEME_PATH . '/page.php') ? 'page.php' : 'index.php';
        } elseif ($nodeData['type'] === 'post') {
            // Try post.php for blog posts (equivalent to WP single.php)
            $template = file_exists(THEME_PATH . '/post.php') ? 'post.php' : 'index.php';
        }

        require_once THEME_PATH . '/' . $template;
    }

    /**
     * Renders lists using archive.php.
     */
    private function renderList($listTitle, $listPosts)
    {
        $title = $listTitle;
        $navigation = $this->indexData['menu'] ?? [];
        $recentPosts = $this->getRecentPosts(5);
        $content = $listPosts; // Pass the array of posts to the archive template

        // Try archive.php, fallback to index.php 
        $template = file_exists(THEME_PATH . '/archive.php') ? 'archive.php' : 'index.php';
        require_once THEME_PATH . '/' . $template;
    }

    private function getRecentPosts($limit = 5)
    {
        return array_slice($this->indexData['posts'], 0, $limit, true);
    }

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

    private function error404()
    {
        http_response_code(404);
        $title = "404 Not Found";
        $content = "<h1>Page not found</h1><p>Sorry, but the content you are looking for does not exist.</p>";
        $navigation = $this->indexData['menu'] ?? [];
        $recentPosts = $this->getRecentPosts(5);

        require_once THEME_PATH . '/index.php';
    }
}