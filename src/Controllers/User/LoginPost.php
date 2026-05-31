<?php

namespace Controllers\User;

use Controllers\ControllerInterface;
use Models\User\User;
use Shared\{CsrfGuard, SessionGuard, RateLimiter};

/**
 * Processes the admin login form submission.
 *
 * @package Controllers\User
 */
class LoginPost implements ControllerInterface
{
    /**
     * @return void
     */
    public function control(): void
    {
        CsrfGuard::check();

        /* 5 tentatives max par minute avant blocage temporaire */
        if (!RateLimiter::check('admin_login', 5, 60)) {
            $_SESSION['login_error'] = 'Trop de tentatives. Veuillez patienter.';
            header('Location: /admin/login');
            exit();
        }

        $email = trim($_POST['email'] ?? '');
        $pass  = $_POST['password']   ?? '';
        $user  = User::findByEmail($email);

        if ($user && User::verifyPassword($pass, $user['password'])) {
            SessionGuard::login((int)$user['id'], $user['nom'], $user['role']);
            User::updateLastLogin((int)$user['id']);
            header('Location: /admin');
        } else {
            $_SESSION['login_error'] = 'Email ou mot de passe incorrect.';
            header('Location: /admin/login');
        }

        exit();
    }

    /**
     * @param string $path
     * @param string $method
     * @return bool
     */
    public static function support(string $path, string $method): bool
    {
        return $path === '/admin/login' && $method === 'POST';
    }
}