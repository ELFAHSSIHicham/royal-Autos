<?php
namespace Controllers\Admin;

use Controllers\ControllerInterface;
use Shared\SessionGuard;
use Models\Voiture\Voiture;

class AdminVoitureListController implements ControllerInterface
{
    public function control(): void
    {
        SessionGuard::requireAdmin();
        $voitures = Voiture::getAllAdmin();
        $success  = $_SESSION['flash_success'] ?? null;
        unset($_SESSION['flash_success']);
        include __DIR__ . '/../../Views/Admin/voitures-list.php';
    }

    public static function support(string $path, string $method): bool
    {
        return $path === '/admin/voitures' && $method === 'GET';
    }
}
