<?php

declare(strict_types=1);

/**
 * Safe HTML escaping (for both text and attributes).
 *
 * @param string|int|float|null $value
 * @param bool $doubleEncode
 * @return string
 */
function e(string|int|float|null $value, bool $doubleEncode = false): string
{
    if ($value === null) {
        return '';
    }

    return htmlspecialchars(
        (string) $value,
        ENT_QUOTES | ENT_SUBSTITUTE,
        'UTF-8',
        $doubleEncode
    );
}

