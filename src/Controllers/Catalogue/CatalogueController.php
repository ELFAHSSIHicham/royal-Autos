<?php
namespace Controllers\Catalogue;

use Controllers\ControllerInterface;
use Models\Voiture\Voiture;
use Views\Catalogue\CatalogueView;

class CatalogueController implements ControllerInterface
{
    public const PATH = '/catalogue';

    public function control(): void
    {
        $filters = [
            'marque_id' => $_GET['marque_id'] ?? '',
            'carburant' => $_GET['carburant'] ?? '',
            'prix_max'  => $_GET['prix_max']  ?? '',
            'prix_min'  => $_GET['prix_min']  ?? '',
            'km_max'    => $_GET['km_max']    ?? '',
            'annee_min' => $_GET['annee_min'] ?? '',
            'search'    => $_GET['search']    ?? '',
        ];
        $page    = max(1, (int)($_GET['page'] ?? 1));
        $result  = Voiture::getAll($filters, $page);
        $marques = Voiture::getMarques();

        $view = new CatalogueView();
        $view->setData([
            'PAGE_TITLE'   => 'Catalogue — Royal Autos',
            'CURRENT_PATH' => self::PATH,
            'voitures'     => $result['data'],
            'total'        => $result['total'],
            'pages'        => $result['pages'],
            'page'         => $page,
            'filters'      => $filters,
            'marques'      => $marques,
        ]);
        echo $view->render();
    }

    public static function support(string $path, string $method): bool
    {
        return $path === self::PATH && $method === 'GET';
    }
}