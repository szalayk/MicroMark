<?php
/**
 * MicroMark CMS - Entry Point
 * Handles initialization and requests.
 */

// error_reporting(E_ALL);
// ini_set('display_errors', 1);

// Set locale for date formatting
setlocale(LC_TIME, 'en_US');

require_once '../app/config.php';

/**
 * Load required classes manually.
 * Note: Consider using Composer PSR-4 autoloading in the future.
 */
require_once CORE_PATH . '/FileHandler.php';
require_once CORE_PATH . '/Cms.php';

use App\Core\Router;
use App\Core\Cms;

// Initialize Router and CMS
$router = new Router();
$cms = new Cms();

// Get the current URI segments
$segments = $router->uriSegments();

// Handle the request
$cms->handleRequest($segments);