<?php
namespace Controllers\User;

use Controllers\ControllerInterface;
use Shared\SessionGuard;

class LoginController implements ControllerInterface
{
    public const PATH = '/admin/login';

    public function control(): void
    {
        if (SessionGuard::isAdmin()) {
            header('Location: /admin');
            exit();
        }
        $error = $_SESSION['login_error'] ?? null;
        unset($_SESSION['login_error']);

        include __DIR__ . '/../../Views/Admin/admin_login.php';
    }

    public static function support(string $path, string $method): bool
    {
        return $path === self::PATH && $method === 'GET';
    }
}
