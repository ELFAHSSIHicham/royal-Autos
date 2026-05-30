<?php

namespace Shared;

/**
 * Enforces role-based access control on top of the base admin session check.
 *
 * @package Shared
 */
class RoleGuard
{
    /**
     * Terminates the request with a 403 if the current admin is not a superadmin.
     * Delegates the base admin check to SessionGuard first.
     *
     * @return void
     */
    public static function requireSuperAdmin(): void
    {
        SessionGuard::requireAdmin();

        if (($_SESSION['admin_role'] ?? '') !== 'superadmin') {
            http_response_code(403);
            include __DIR__ . '/../Views/Errors/403.php';
            exit();
        }
    }

    /**
     * Returns true if the current session belongs to a superadmin.
     *
     * @return bool
     */
    public static function isSuperAdmin(): bool
    {
        return ($_SESSION['admin_role'] ?? '') === 'superadmin';
    }
}