<?php

declare(strict_types=1);

namespace App\Core;

/**
 * Class FileHandler
 * Handles file reading and parsing, including FrontMatter.
 */
class FileHandler
{
    /**
     * Parse a markdown file with FrontMatter safely within CONTENT_PATH.
     *
     * @param string $filePath Relative path inside CONTENT_PATH (e.g. "posts/hello.md") or absolute path inside CONTENT_PATH.
     * @return array{meta: array<string, string>, content: string}|null
     */
    public static function parseFile(string $filePath): ?array
    {
        $resolvedPath = self::resolveSafeContentPath($filePath);
        if ($resolvedPath === null) {
            return null;
        }

        $content = file_get_contents($resolvedPath);
        if ($content === false) {
            return null;
        }

        // Normalize CRLF / CR line endings to LF
        $content = str_replace(["\r\n", "\r"], "\n", $content);

        // Separate FrontMatter and body content (--- blocks)
        $pattern = "/^---\s*\n(.*?)\n---\s*(?:\n(.*))?$/s";

        if (preg_match($pattern, $content, $matches)) {
            $yamlRaw = $matches[1];
            $body = $matches[2] ?? '';

            // Basic key-value pair parsing from FrontMatter
            $meta = [];
            $lines = explode("\n", $yamlRaw);
            foreach ($lines as $line) {
                $line = trim($line);
                if ($line === '' || str_starts_with($line, '#')) {
                    continue;
                }

                if (str_contains($line, ':')) {
                    [$key, $value] = explode(':', $line, 2);
                    $cleanKey = trim($key);
                    if ($cleanKey !== '') {
                        $meta[$cleanKey] = self::unquote(trim($value));
                    }
                }
            }

            return [
                'meta' => $meta,
                'content' => $body,
            ];
        }

        // If no FrontMatter is found, return the entire content as body
        return [
            'meta' => [],
            'content' => $content,
        ];
    }

    /**
     * Ensures the target file exists, is a regular .md file, and resides inside CONTENT_PATH.
     */
    private static function resolveSafeContentPath(string $filePath): ?string
    {
        if ($filePath === '' || str_contains($filePath, "\0")) {
            return null;
        }

        $baseDir = realpath(CONTENT_PATH);
        if ($baseDir === false) {
            return null;
        }

        $candidate = str_starts_with($filePath, DIRECTORY_SEPARATOR)
            ? $filePath
            : $baseDir . DIRECTORY_SEPARATOR . ltrim($filePath, '/\\');

        $realFile = realpath($candidate);
        if ($realFile === false || !is_file($realFile)) {
            return null;
        }

        $basePrefix = rtrim($baseDir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        if (!str_starts_with($realFile, $basePrefix)) {
            return null;
        }

        if (strtolower(pathinfo($realFile, PATHINFO_EXTENSION)) !== 'md') {
            return null;
        }

        return $realFile;
    }

    /**
     * Strips matching surrounding single or double quotes from scalar values.
     */
    private static function unquote(string $value): string
    {
        $len = strlen($value);
        if ($len >= 2) {
            $first = $value[0];
            $last = $value[$len - 1];
            if (($first === '"' && $last === '"') || ($first === "'" && $last === "'")) {
                return substr($value, 1, -1);
            }
        }

        return $value;
    }
}