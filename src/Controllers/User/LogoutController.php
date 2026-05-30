<?php

namespace Controllers\User;

use Controllers\ControllerInterface;
use Shared\SessionGuard;

/**
 * Destroys the admin session and redirects to the login page.
 *
 * @package Controllers\User
 */
class LogoutController implements ControllerInterface
{
    /**
     * @return void
     */
    public function control(): void
    {
        SessionGuard::logout();
        header('Location: /admin/login');
        exit();
    }

    /**
     * @param string $path
     * @param string $method
     * @return bool
     */
    public static function support(string $path, string $method): bool
    {
        return $path === '/admin/logout';
    }
}