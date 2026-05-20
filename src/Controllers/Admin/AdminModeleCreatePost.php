<?php
namespace Controllers\Admin;

use Controllers\ControllerInterface;
use Shared\{CsrfGuard, SessionGuard, Sanitizer};
use Models\Voiture\Voiture;

class AdminModeleCreatePost implements ControllerInterface
{
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
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Ce modèle existe déjà pour cette marque.']);
        }
        exit();
    }

    public static function support(string $path, string $method): bool
    {
        return $path === '/admin/modeles/nouveau' && $method === 'POST';
    }
}