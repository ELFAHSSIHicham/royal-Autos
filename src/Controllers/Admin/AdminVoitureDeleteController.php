<?php
namespace Controllers\Admin;

use Controllers\ControllerInterface;
use Shared\{CsrfGuard, SessionGuard};
use Models\Voiture\Voiture;

class AdminVoitureDeleteController implements ControllerInterface
{
    public function control(): void
    {
        SessionGuard::requireAdmin();
        CsrfGuard::check();
        $id = (int)(explode('/', $_SERVER['REQUEST_URI'])[4] ?? 0);
        Voiture::delete($id);
        $_SESSION['flash_success'] = 'Voiture supprimée.';
        header('Location: /admin/voitures');
        exit();
    }

    public static function support(string $path, string $method): bool
    {
        return preg_match('#^/admin/voitures/supprimer/\d+$#', $path) && $method === 'POST';
    }
}
