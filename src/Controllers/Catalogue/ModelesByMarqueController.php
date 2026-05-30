<?php

namespace Controllers\Catalogue;

use Models\Voiture\Modele;

/**
 * JSON endpoint returning models for a given brand.
 * Used by the catalogue filter form to populate the model dropdown dynamically.
 *
 * @package Controllers\Catalogue
 */
class ModelesByMarqueController
{
    /**
     * @param string $path
     * @param string $method
     * @return bool
     */
    public static function support(string $path, string $method): bool
    {
        return $path === '/api/modeles' && $method === 'GET';
    }

    /**
     * @return void
     */
    public function control(): void
    {
        header('Content-Type: application/json; charset=utf-8');

        $marqueId = isset($_GET['marque_id']) ? (int)$_GET['marque_id'] : 0;

        /* Retourne un tableau vide si l'ID est absent ou invalide */
        if ($marqueId <= 0) {
            echo json_encode([]);
            exit;
        }

        echo json_encode(Modele::getByMarque($marqueId));
        exit;
    }
}