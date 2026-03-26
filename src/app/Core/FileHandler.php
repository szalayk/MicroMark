<?php

namespace App\Core;

/**
 * Class FileHandler
 * Handles file reading and parsing, including FrontMatter.
 */
class FileHandler
{
    /**
     * Parse a markdown file with FrontMatter.
     *
     * @param string $filePath Path to the file to parse.
     * @return array|null Returns an array with 'meta' and 'content' keys, or null if file doesn't exist.
     */
    public static function parseFile($filePath)
    {
        if (!file_exists($filePath)) {
            return null;
        }

        $content = file_get_contents($filePath);
        
        // Separate FrontMatter and body content (--- blocks)
        $pattern = "/^---\s*\n(.*?)\n---\s*\n(.*)/s";
        
        if (preg_match($pattern, $content, $matches)) {
            $yamlRaw = $matches[1];
            $body = $matches[2];
            
            // Basic key-value pair parsing from "YAML"
            $meta = [];
            $lines = explode("\n", $yamlRaw);
            foreach ($lines as $line) {
                if (strpos($line, ':') !== false) {
                    list($key, $value) = explode(':', $line, 2);
                    $meta[trim($key)] = trim($value);
                }
            }
            
            return [
                'meta' => $meta,
                'content' => $body
            ];
        }

        // If no FrontMatter is found, return the entire content as body
        return [
            'meta' => [],
            'content' => $content
        ];
    }
}