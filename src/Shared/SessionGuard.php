<?php
namespace Shared;

class SessionGuard
{
    public static function isAdmin(): bool
    {
        return !empty($_SESSION['admin']) && !empty($_SESSION['admin_id']);
    }

    public static function requireAdmin(): void
    {
        if (!self::isAdmin()) {
            header('Location: /admin/login');
            exit();
        }
    }

    public static function login(int $id, string $nom, string $role): void
    {
        session_regenerate_id(true);
        $_SESSION['admin']      = true;
        $_SESSION['admin_id']   = $id;
        $_SESSION['admin_nom']  = $nom;
        $_SESSION['admin_role'] = $role;
    }

    public static function logout(): void
    {
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $p = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $p['path'], $p['domain'], $p['secure'], $p['httponly']);
        }
        session_destroy();
    }
}
