<?php
namespace Controllers\Reservation;

use Controllers\ControllerInterface;
use Models\Voiture\Voiture;
use Views\Reservation\ReservationView;

class ReservationController implements ControllerInterface
{
    public function control(): void
    {
        $slug    = $_GET['slug'] ?? '';
        $voiture = $slug ? Voiture::getBySlug($slug) : null;

        if (!$voiture || $voiture['statut'] !== 'disponible') {
            http_response_code(404);
            include __DIR__ . '/../../Views/Errors/404.php';
            return;
        }

        $errors = $_SESSION['resa_errors'] ?? [];
        $old    = $_SESSION['resa_old']    ?? [];
        unset($_SESSION['resa_errors'], $_SESSION['resa_old']);

        $view = new ReservationView();
        $view->setData([
            'PAGE_TITLE'   => 'Réserver — ' . $voiture['marque'] . ' ' . $voiture['modele'],
            'CURRENT_PATH' => '/reservation',
            'voiture'      => $voiture,
            'errors'       => $errors,
            'old'          => $old,
        ]);
        echo $view->render();
    }

    public static function support(string $path, string $method): bool
    {
        return $path === '/reservation' && $method === 'GET';
    }
}
