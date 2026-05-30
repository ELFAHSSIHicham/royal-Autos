<?php

namespace Shared;

/**
 * Session-based rate limiter scoped by action key and client IP.
 *
 * @package Shared
 */
class RateLimiter
{
    /**
     * Returns false if the caller has exceeded the allowed request count
     * within the given time window. The counter resets automatically after expiry.
     *
     * @param string $key    Action identifier (e.g. 'contact', 'admin_login')
     * @param int    $max    Maximum allowed attempts within the window
     * @param int    $window Time window in seconds
     * @return bool True if the request is allowed, false if the quota is exceeded
     */
    public static function check(string $key, int $max = 5, int $window = 60): bool
    {
        /* Clé de session unique par action et par adresse IP */
        $sKey = '_rl_' . $key . '_' . md5($_SERVER['REMOTE_ADDR'] ?? '');
        $now  = time();

        if (!isset($_SESSION[$sKey]) || $now - $_SESSION[$sKey]['start'] > $window) {
            $_SESSION[$sKey] = ['count' => 0, 'start' => $now];
        }

        $_SESSION[$sKey]['count']++;
        return $_SESSION[$sKey]['count'] <= $max;
    }
}