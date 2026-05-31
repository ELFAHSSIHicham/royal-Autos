<?php

namespace Controllers\Admin;

use Controllers\ControllerInterface;
use Shared\SessionGuard;
use Models\Voiture\Voiture;

/**
 * Displays the vehicle creation form.
 *
 * @package Controllers\Admin
 */
class AdminVoitureCreateController implements ControllerInterface
{
    /**
     * @return void
     */
    public function control(): void
    {
        SessionGuard::requireAdmin();

        $errors  = $_SESSION['form_errors'] ?? [];
        $old     = $_SESSION['form_old']    ?? [];
        unset($_SESSION['form_errors'], $_SESSION['form_old']);

        $voiture = [];
        $mode    = 'create';
        $marques = Voiture::getMarques();

        include __DIR__ . '/../../Views/Admin/voiture-form.php';
    }

    /**
     * @param string $path
     * @param string $method
     * @return bool
     */
    public static function support(string $path, string $method): bool
    {
        return $path === '/admin/voitures/nouveau' && $method === 'GET';
    }
}