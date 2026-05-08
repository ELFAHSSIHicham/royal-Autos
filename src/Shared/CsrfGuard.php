<?php
namespace Shared;

class CsrfGuard
{
    private const KEY = '_csrf_token';

    public static function generate(): string
    {
        if (empty($_SESSION[self::KEY])) {
            $_SESSION[self::KEY] = bin2hex(random_bytes(32));
        }
        return $_SESSION[self::KEY];
    }

    public static function field(): string
    {
        return '<input type="hidden" name="_csrf" value="'
            . htmlspecialchars(self::generate(), ENT_QUOTES) . '">';
    }

    public static function check(): void
    {
        $token = $_POST['_csrf'] ?? '';
        if (empty($_SESSION[self::KEY]) || !hash_equals($_SESSION[self::KEY], $token)) {
            http_response_code(403);
            include __DIR__ . '/../Views/Errors/403.php';
            exit();
        }
        unset($_SESSION[self::KEY]);
    }
}
