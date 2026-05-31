<?php

namespace Controllers\Admin;

use Controllers\ControllerInterface;
use Shared\SessionGuard;
use Models\Voiture\Voiture;
use Models\Reservation\Reservation;
use Models\Contact\ContactMessage;

/**
 * Renders the admin dashboard with key statistics.
 *
 * @package Controllers\Admin
 */
class AdminDashboardController implements ControllerInterface
{
    /** @var string Route handled by this controller */
    public const PATH = '/admin';

    /**
     * @return void
     */
    public function control(): void
    {
        SessionGuard::requireAdmin();

        /* On ne remonte que les 5 dernières réservations pour l'aperçu */
        $data = [
            'PAGE_TITLE'     => 'Dashboard — Admin Royal Autos',
            'CURRENT_PATH'   => self::PATH,
            'nbVoitures'     => Voiture::countDisponibles(),
            'nbReservations' => Reservation::countByStatut('en_attente'),
            'nbMessages'     => ContactMessage::countUnread(),
            'reservations'   => array_slice(Reservation::getAll(), 0, 5),
        ];

        extract($data);
        include __DIR__ . '/../../Views/Admin/dashboard.php';
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