<?php
namespace Controllers\Admin;

use Controllers\ControllerInterface;
use Shared\SessionGuard;

class AdminVoitureCreateController implements ControllerInterface
{
    public function control(): void
    {
        SessionGuard::requireAdmin();
        $errors = $_SESSION['form_errors'] ?? [];
        $old    = $_SESSION['form_old']    ?? [];
        unset($_SESSION['form_errors'], $_SESSION['form_old']);
        $mode = 'create';
        include __DIR__ . '/../../Views/Admin/voiture-form.php';
    }

    public static function support(string $path, string $method): bool
    {
        return $path === '/admin/voitures/nouveau' && $method === 'GET';
    }
}
