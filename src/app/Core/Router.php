<?php

namespace App\Core;

/**
 * Class Router
 * Responsible for parsing the URI and handling routing.
 */
class Router
{

    /**
     * Parse URI segments into an array.
     *
     * @param int|null $index Optional index to return a specific segment.
     * @return array|string|bool Returns the full segments array, a specific segment, or false if not found.
     */
    public function uriSegments($index = null) {
        $url = isset($_GET['url']) ? $_GET['url'] : '';
        $segments = explode('/', trim($url, '/'));

        if ($index === null) {
            return $segments;
        }

        return isset($segments[$index]) ? $segments[$index] : false;
    }

    /**
     * 404 error handling.
     * Sends a 404 HTTP response code and displays an error message.
     */
    private function error404() {
        http_response_code(404);
        echo "404 - Page not found.";
    }

}
