<?php

namespace Controllers\User;

use Controllers\ControllerInterface;
use Shared\SessionGuard;

/**
 * Displays the admin login page.
 * Redirects to /admin if a session is already active.
 *
 * @package Controllers\User
 */
class LoginController implements ControllerInterface
{
    /** @var string Route handled by this controller */
    public const PATH = '/admin/login';

    /**
     * @return void
     */
    public function control(): void
    {
        /* Évite d'afficher le formulaire si l'admin est déjà connecté */
        if (SessionGuard::isAdmin()) {
            header('Location: /admin');
            exit();
        }

        $error = $_SESSION['login_error'] ?? null;
        unset($_SESSION['login_error']);

        include __DIR__ . '/../../Views/Admin/admin_login.php';
    }

    /**
     * @param string $path
     * @param string $method
     * @return bool
     */
    public static function support(string $path, string $method): bool
    {
        return $path === self::PATH && $method === 'GET';
    }
}