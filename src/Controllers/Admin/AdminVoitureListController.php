<?php

namespace Controllers\Admin;

use Controllers\ControllerInterface;
use Shared\SessionGuard;
use Models\Voiture\Voiture;

/**
 * Displays the admin vehicle list.
 *
 * @package Controllers\Admin
 */
class AdminVoitureListController implements ControllerInterface
{
    /**
     * @return void
     */
    public function control(): void
    {
        SessionGuard::requireAdmin();

        $voitures = Voiture::getAllAdmin();
        $success  = $_SESSION['flash_success'] ?? null;
        unset($_SESSION['flash_success']);

        include __DIR__ . '/../../Views/Admin/voitures-list.php';
    }

    /**
     * @param string $path
     * @param string $method
     * @return bool
     */
    public static function support(string $path, string $method): bool
    {
        return $path === '/admin/voitures' && $method === 'GET';
    }
}