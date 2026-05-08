<?php
namespace Shared;

class RateLimiter
{
    /**
     * Retourne false si le quota est dépassé.
     * $max tentatives sur $window secondes, par clé + IP.
     */
    public static function check(string $key, int $max = 5, int $window = 60): bool
    {
        $sKey = '_rl_' . $key . '_' . md5($_SERVER['REMOTE_ADDR'] ?? '');
        $now  = time();

        if (!isset($_SESSION[$sKey]) || $now - $_SESSION[$sKey]['start'] > $window) {
            $_SESSION[$sKey] = ['count' => 0, 'start' => $now];
        }

        $_SESSION[$sKey]['count']++;
        return $_SESSION[$sKey]['count'] <= $max;
    }
}
