<?php
namespace Controllers\Catalogue;

use Models\Voiture\Modele;

class ModelesByMarqueController
{
    public static function support(string $path, string $method): bool
    {
        return $path === '/api/modeles' && $method === 'GET';
    }

    public function control(): void
    {
        header('Content-Type: application/json; charset=utf-8');

        $marqueId = isset($_GET['marque_id']) ? (int)$_GET['marque_id'] : 0;

        if ($marqueId <= 0) {
            echo json_encode([]);
            exit;
        }

        echo json_encode(Modele::getByMarque($marqueId));
        exit;
    }
}