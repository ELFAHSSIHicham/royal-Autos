<?php

namespace Shared;

/**
 * Sanitizes raw user input before storage or display.
 * Each method targets a specific data type.
 *
 * @package Shared
 */
class Sanitizer
{
    /**
     * Trims and HTML-encodes a string value.
     *
     * @param mixed $v
     * @return string
     */
    public static function str(mixed $v): string
    {
        return htmlspecialchars(trim((string)$v), ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }

    /**
     * Extracts and casts an integer from the value.
     *
     * @param mixed $v
     * @return int
     */
    public static function int(mixed $v): int
    {
        return (int) filter_var($v, FILTER_SANITIZE_NUMBER_INT);
    }

    /**
     * Extracts and casts a float from the value, allowing decimal fractions.
     *
     * @param mixed $v
     * @return float
     */
    public static function float(mixed $v): float
    {
        return (float) filter_var($v, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    }

    /**
     * Sanitizes an email address by stripping invalid characters.
     *
     * @param mixed $v
     * @return string
     */
    public static function email(mixed $v): string
    {
        return (string) filter_var(trim((string)$v), FILTER_SANITIZE_EMAIL);
    }

    /**
     * Converts a string to a URL-safe slug.
     * Replaces accented characters, lowercases, and collapses non-alphanumeric sequences into hyphens.
     *
     * @param string $v
     * @return string
     */
    public static function slug(string $v): string
    {
        /* Table de translittération pour les caractères accentués français */
        $map = [
            'é'=>'e','è'=>'e','ê'=>'e','ë'=>'e',
            'à'=>'a','â'=>'a','ä'=>'a',
            'ù'=>'u','û'=>'u','ü'=>'u',
            'ô'=>'o','ö'=>'o',
            'î'=>'i','ï'=>'i',
            'ç'=>'c',
        ];

        $v = mb_strtolower(trim($v));
        $v = strtr($v, $map);
        $v = preg_replace('/[^a-z0-9]+/', '-', $v) ?? $v;
        return trim($v, '-');
    }
}