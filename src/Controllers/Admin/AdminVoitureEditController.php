<?php

namespace Controllers\Admin;

use Controllers\ControllerInterface;
use Shared\SessionGuard;
use Models\Voiture\Voiture;

/**
 * Displays the vehicle edit form pre-filled with existing data.
 *
 * @package Controllers\Admin
 */
class AdminVoitureEditController implements ControllerInterface
{
    /**
     * @return void
     */
    public function control(): void
    {
        SessionGuard::requireAdmin();

        /* L'ID du véhicule est le 5ème segment de l'URI : /admin/voitures/modifier/{id} */
        $id      = (int)(explode('/', $_SERVER['REQUEST_URI'])[4] ?? 0);
        $voiture = Voiture::getById($id);

        if (!$voiture) {
            http_response_code(404);
            include __DIR__ . '/../../Views/Errors/404.php';
            return;
        }

        $errors  = $_SESSION['form_errors'] ?? [];
        $old     = $_SESSION['form_old']    ?? $voiture;
        unset($_SESSION['form_errors'], $_SESSION['form_old']);

        $images  = Voiture::getImages($id);
        $mode    = 'edit';
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
        return preg_match('#^/admin/voitures/modifier/\d+$#', $path) && $method === 'GET';
    }
}