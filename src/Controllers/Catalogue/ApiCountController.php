<?php
namespace Controllers\Catalogue;

use Models\Voiture\Voiture;

class ApiCountController
{
    public static function support(string $path, string $method): bool
    {
        return $path === '/api/count' && $method === 'GET';
    }

    public function control(): void
    {
        header('Content-Type: application/json; charset=utf-8');

        $filters = [
            'marque_id'    => $_GET['marque_id']    ?? '',
            'modele_id'    => $_GET['modele_id']    ?? '',
            'carburant'    => $_GET['carburant']    ?? '',
            'transmission' => $_GET['transmission'] ?? '',
            'prix_min'     => $_GET['prix_min']     ?? '',
            'prix_max'     => $_GET['prix_max']     ?? '',
            'annee_min'    => $_GET['annee_min']    ?? '',
            'annee_max'    => $_GET['annee_max']    ?? '',
            'km_max'       => $_GET['km_max']       ?? '',
            'search'       => $_GET['search']       ?? '',
        ];

        $result = Voiture::getAll($filters, 1, 1);
        echo json_encode(['count' => $result['total']]);
        exit;
    }
}