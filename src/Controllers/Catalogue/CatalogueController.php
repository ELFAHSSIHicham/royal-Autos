<?php

namespace Controllers\Catalogue;

use Controllers\ControllerInterface;
use Models\Voiture\Voiture;
use Views\Catalogue\CatalogueView;

/**
 * Displays the vehicle catalogue with filters and pagination.
 *
 * @package Controllers\Catalogue
 */
class CatalogueController implements ControllerInterface
{
    /** @var string Route handled by this controller */
    public const PATH = '/catalogue';

    /**
     * @return void
     */
    public function control(): void
    {
        /* Lecture des filtres GET avec valeurs par défaut vides */
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

        $page   = max(1, (int)($_GET['page'] ?? 1));
        $result = Voiture::getAll($filters, $page);

        $view = new CatalogueView();
        $view->setData([
            'PAGE_TITLE'   => 'Catalogue — Royal Autos',
            'CURRENT_PATH' => self::PATH,
            'voitures'     => $result['data'],
            'total'        => $result['total'],
            'pages'        => $result['pages'],
            'page'         => $page,
            'filters'      => $filters,
            'marques'      => Voiture::getMarques(),
        ]);
        echo $view->render();
    }

    /**
     * @param string $path
     * @param string $method
     * @return bool
     */
    public static function support(string $path, string $method): bool
    {
        return $path === self::PATH && $method === 'GET';
    }
}