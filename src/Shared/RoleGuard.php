<?php
namespace Shared;

class RoleGuard
{
    public static function requireSuperAdmin(): void
    {
        SessionGuard::requireAdmin();
        if (($_SESSION['admin_role'] ?? '') !== 'superadmin') {
            http_response_code(403);
            include __DIR__ . '/../Views/Errors/403.php';
            exit();
        }
    }

    public static function isSuperAdmin(): bool
    {
        return ($_SESSION['admin_role'] ?? '') === 'superadmin';
    }
}
