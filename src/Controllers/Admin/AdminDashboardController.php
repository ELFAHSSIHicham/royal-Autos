<?php
namespace Controllers\Admin;

use Controllers\ControllerInterface;
use Shared\SessionGuard;
use Models\Voiture\Voiture;
use Models\Reservation\Reservation;
use Models\Contact\ContactMessage;

class AdminDashboardController implements ControllerInterface
{
    public const PATH = '/admin';

    public function control(): void
    {
        SessionGuard::requireAdmin();

        $data = [
            'PAGE_TITLE'        => 'Dashboard — Admin Royal Autos',
            'CURRENT_PATH'      => self::PATH,
            'nbVoitures'        => Voiture::countDisponibles(),
            'nbReservations'    => Reservation::countByStatut('en_attente'),
            'nbMessages'        => ContactMessage::countUnread(),
            'reservations'      => array_slice(Reservation::getAll(), 0, 5),
        ];

        extract($data);
        include __DIR__ . '/../../Views/Admin/dashboard.php';
    }

    public static function support(string $path, string $method): bool
    {
        return $path === self::PATH && $method === 'GET';
    }
}