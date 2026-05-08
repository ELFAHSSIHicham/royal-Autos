<?php
namespace Controllers\User;

use Controllers\ControllerInterface;
use Shared\SessionGuard;

class LogoutController implements ControllerInterface
{
    public function control(): void
    {
        SessionGuard::logout();
        header('Location: /admin/login');
        exit();
    }

    public static function support(string $path, string $method): bool
    {
        return $path === '/admin/logout';
    }
}
