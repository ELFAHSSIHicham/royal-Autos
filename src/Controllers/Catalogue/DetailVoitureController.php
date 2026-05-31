<?php

namespace Controllers\Catalogue;

use Controllers\ControllerInterface;
use Models\Voiture\Voiture;
use Views\Catalogue\DetailView;

/**
 * Displays the detail page for a single vehicle identified by its slug.
 *
 * @package Controllers\Catalogue
 */
class DetailVoitureController implements ControllerInterface
{
    /**
     * @return void
     */
    public function control(): void
    {
        $uri  = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/';
        $slug = trim(substr($uri, strlen('/voiture/')));

        $voiture = $slug ? Voiture::getBySlug($slug) : null;

        if (!$voiture) {
            http_response_code(404);
            include __DIR__ . '/../../Views/Errors/404.php';
            return;
        }

        $view = new DetailView();
        $view->setData([
            'PAGE_TITLE'   => $voiture['marque'] . ' ' . $voiture['modele'] . ' — Royal Autos',
            'CURRENT_PATH' => '/voiture/' . $slug,
            'voiture'      => $voiture,
            'images'       => Voiture::getImages((int)$voiture['id']),
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
        return str_starts_with($path, '/voiture/') && $method === 'GET';
    }
}