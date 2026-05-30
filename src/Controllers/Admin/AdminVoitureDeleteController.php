<?php

namespace Controllers\Admin;

use Controllers\ControllerInterface;
use Shared\{CsrfGuard, SessionGuard};
use Models\Voiture\Voiture;

/**
 * Handles vehicle deletion from the admin panel.
 *
 * @package Controllers\Admin
 */
class AdminVoitureDeleteController implements ControllerInterface
{
    /**
     * @return void
     */
    public function control(): void
    {
        SessionGuard::requireAdmin();
        CsrfGuard::check();

        /* L'ID du véhicule est le 5ème segment de l'URI : /admin/voitures/supprimer/{id} */
        $id = (int)(explode('/', $_SERVER['REQUEST_URI'])[4] ?? 0);
        Voiture::delete($id);

        $_SESSION['flash_success'] = 'Voiture supprimée.';
        header('Location: /admin/voitures');
        exit();
    }

    /**
     * @param string $path
     * @param string $method
     * @return bool
     */
    public static function support(string $path, string $method): bool
    {
        return preg_match('#^/admin/voitures/supprimer/\d+$#', $path) && $method === 'POST';
    }
}