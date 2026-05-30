<?php

namespace Controllers\Admin;

use Controllers\ControllerInterface;
use Shared\{CsrfGuard, SessionGuard, Sanitizer};
use Models\Voiture\Voiture;

/**
 * JSON endpoint to create a new vehicle model linked to a brand.
 *
 * @package Controllers\Admin
 */
class AdminModeleCreatePost implements ControllerInterface
{
    /**
     * @return void
     */
    public function control(): void
    {
        SessionGuard::requireAdmin();
        CsrfGuard::check();

        $marqueId = Sanitizer::int($_POST['marque_id'] ?? 0);
        $nom      = Sanitizer::str($_POST['nom']       ?? '');

        if (!$marqueId || !$nom) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Marque et nom sont obligatoires.']);
            exit();
        }

        try {
            $id = Voiture::createModele($marqueId, $nom);
            header('Content-Type: application/json');
            echo json_encode(['id' => $id, 'nom' => $nom, 'marque_id' => $marqueId]);
        } catch (\Exception $e) {
            /* Violation de contrainte d'unicité en base */
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Ce modèle existe déjà pour cette marque.']);
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
        return $path === '/admin/modeles/nouveau' && $method === 'POST';
    }
}