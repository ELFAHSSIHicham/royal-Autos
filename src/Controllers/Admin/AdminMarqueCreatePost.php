<?php
namespace Controllers\Admin;

use Controllers\ControllerInterface;
use Shared\{CsrfGuard, SessionGuard, Sanitizer};
use Models\Voiture\Voiture;

class AdminMarqueCreatePost implements ControllerInterface
{
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
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Cette marque existe déjà.']);
        }
        exit();
    }

    public static function support(string $path, string $method): bool
    {
        return $path === '/admin/marques/nouveau' && $method === 'POST';
    }
}