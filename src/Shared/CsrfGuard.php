<?php

namespace Shared;

/**
 * Generates and validates CSRF tokens for all POST forms.
 * The token is stored in the session and consumed on each successful check.
 *
 * @package Shared
 */
class CsrfGuard
{
    /** @var string Session key used to store the CSRF token */
    private const KEY = '_csrf_token';

    /**
     * Generates a token if none exists yet, then returns it.
     *
     * @return string Hex-encoded 256-bit random token
     */
    public static function generate(): string
    {
        if (empty($_SESSION[self::KEY])) {
            $_SESSION[self::KEY] = bin2hex(random_bytes(32));
        }
        return $_SESSION[self::KEY];
    }

    /**
     * Returns a ready-to-embed hidden input field containing the CSRF token.
     *
     * @return string HTML input element
     */
    public static function field(): string
    {
        return '<input type="hidden" name="_csrf" value="'
            . htmlspecialchars(self::generate(), ENT_QUOTES) . '">';
    }

    /**
     * Validates the token submitted via POST against the session token.
     * Terminates with a 403 response on mismatch.
     * Consumes the token after a successful check to prevent replay.
     *
     * @return void
     */
    public static function check(): void
    {
        $token = $_POST['_csrf'] ?? '';

        /* hash_equals évite les attaques temporelles sur la comparaison */
        if (empty($_SESSION[self::KEY]) || !hash_equals($_SESSION[self::KEY], $token)) {
            http_response_code(403);
            include __DIR__ . '/../Views/Errors/403.php';
            exit();
        }

        unset($_SESSION[self::KEY]);
    }
}