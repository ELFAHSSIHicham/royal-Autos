<?php
namespace Controllers\User;

use Controllers\ControllerInterface;
use Models\User\User;
use Shared\{CsrfGuard, SessionGuard, RateLimiter};

class LoginPost implements ControllerInterface
{
    public function control(): void
    {
        CsrfGuard::check();

        if (!RateLimiter::check('admin_login', 5, 60)) {
            $_SESSION['login_error'] = 'Trop de tentatives. Veuillez patienter.';
            header('Location: /admin/login');
            exit();
        }

        $email = trim($_POST['email'] ?? '');
        $pass  = $_POST['password'] ?? '';
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

    public static function support(string $path, string $method): bool
    {
        return $path === '/admin/login' && $method === 'POST';
    }
}
