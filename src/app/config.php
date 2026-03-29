<?php
/**
 * Configuration file for MicroMark CMS.
 * This file contains path definitions and basic settings.
 */

// Active theme name
$theme = "MicroSkeleton";

/**
 * Build security key. 
 * Change this to your own secret password for triggering the index build.
 */
define('BUILD_KEY', 'my-secret-password-123');

/**
 * Define base path (one level above the app directory).
 */
define('BASE_PATH', realpath(__DIR__ . '/../'));

/**
 * System paths - all relative to BASE_PATH.
 */
define('APP_PATH', BASE_PATH . '/app');
define('CORE_PATH', APP_PATH . '/Core');
define('LIB_PATH', APP_PATH . '/Libraries');
define('CACHE_PATH', BASE_PATH . '/cache');

/**
 * Content and Theme paths.
 */
define('CONTENT_PATH', BASE_PATH . '/content');
define('THEME_PATH', BASE_PATH . '/public/themes/' . $theme);

/**
 * Site URL for links and assets.
 */
define('SITE_URL', 'https://micromark.local');
define('THEME_URL', SITE_URL . '/themes/' . $theme);

/**
 * Manual loading of essential classes (if not using Composer).
 */
require_once CORE_PATH . '/Router.php';
require_once LIB_PATH . '/Parsedown.php';