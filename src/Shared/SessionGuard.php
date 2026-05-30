<?php

namespace Shared;

/**
 * Manages admin session lifecycle: login, logout, and access enforcement.
 *
 * @package Shared
 */
class SessionGuard
{
    /**
     * Returns true if a valid admin session is active.
     *
     * @return bool
     */
    public static function isAdmin(): bool
    {
        return !empty($_SESSION['admin']) && !empty($_SESSION['admin_id']);
    }

    /**
     * Redirects to the login page if no admin session is active.
     *
     * @return void
     */
    public static function requireAdmin(): void
    {
        if (!self::isAdmin()) {
            header('Location: /admin/login');
            exit();
        }
    }

    /**
     * Registers admin credentials in the session.
     * Regenerates the session ID to prevent session fixation attacks.
     *
     * @param int    $id
     * @param string $nom
     * @param string $role
     * @return void
     */
    public static function login(int $id, string $nom, string $role): void
    {
        /* Régénération de l'ID de session pour prévenir la fixation de session */
        session_regenerate_id(true);
        $_SESSION['admin']      = true;
        $_SESSION['admin_id']   = $id;
        $_SESSION['admin_nom']  = $nom;
        $_SESSION['admin_role'] = $role;
    }

    /**
     * Destroys the session and clears the session cookie.
     *
     * @return void
     */
    public static function logout(): void
    {
        $_SESSION = [];

        /* Suppression explicite du cookie de session côté navigateur */
        if (ini_get('session.use_cookies')) {
            $p = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $p['path'], $p['domain'], $p['secure'], $p['httponly']);
        }

        session_destroy();
    }
}