<?php
namespace Controllers\Catalogue;

use Controllers\ControllerInterface;
use Models\Voiture\Voiture;
use Views\Catalogue\DetailView;

class DetailVoitureController implements ControllerInterface
{
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

        $images = Voiture::getImages((int)$voiture['id']);

        $view = new DetailView();
        $view->setData([
            'PAGE_TITLE'   => $voiture['marque'] . ' ' . $voiture['modele'] . ' — Royal Autos',
            'CURRENT_PATH' => '/voiture/' . $slug,
            'voiture'      => $voiture,
            'images'       => $images,
        ]);
        echo $view->render();
    }

    public static function support(string $path, string $method): bool
    {
        return str_starts_with($path, '/voiture/') && $method === 'GET';
    }
}