<?php

namespace Controllers\Admin;

use Controllers\ControllerInterface;
use Shared\{CsrfGuard, SessionGuard, Sanitizer};
use Models\Voiture\Voiture;

/**
 * JSON endpoint to create a new vehicle brand from the admin form.
 *
 * @package Controllers\Admin
 */
class AdminMarqueCreatePost implements ControllerInterface
{
    /**
     * @return void
     */
    public function control(): void
    {
        SessionGuard::requireAdmin();
        CsrfGuard::check();

        $nom = Sanitizer::str($_POST['nom'] ?? '');

        if (!$nom) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Le nom est obligatoire.']);
            exit();
        }

        try {
            $id = Voiture::createMarque($nom);
            header('Content-Type: application/json');
            echo json_encode(['id' => $id, 'nom' => $nom]);
        } catch (\Exception $e) {
            /* Violation de contrainte d'unicité en base */
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Cette marque existe déjà.']);
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
        return $path === '/admin/marques/nouveau' && $method === 'POST';
    }
}