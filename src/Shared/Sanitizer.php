<?php
namespace Shared;

class Sanitizer
{
    public static function str(mixed $v): string
    {
        return htmlspecialchars(trim((string)$v), ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }

    public static function int(mixed $v): int
    {
        return (int) filter_var($v, FILTER_SANITIZE_NUMBER_INT);
    }

    public static function float(mixed $v): float
    {
        return (float) filter_var($v, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    }

    public static function email(mixed $v): string
    {
        return (string) filter_var(trim((string)$v), FILTER_SANITIZE_EMAIL);
    }

    public static function slug(string $v): string
    {
        $map = ['é'=>'e','è'=>'e','ê'=>'e','ë'=>'e','à'=>'a','â'=>'a','ä'=>'a',
                'ù'=>'u','û'=>'u','ü'=>'u','ô'=>'o','ö'=>'o','î'=>'i','ï'=>'i','ç'=>'c'];
        $v = mb_strtolower(trim($v));
        $v = strtr($v, $map);
        $v = preg_replace('/[^a-z0-9]+/', '-', $v) ?? $v;
        return trim($v, '-');
    }
}
