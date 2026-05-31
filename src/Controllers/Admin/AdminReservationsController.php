<?php

namespace Controllers\Admin;

use Controllers\ControllerInterface;
use Shared\SessionGuard;
use Models\Reservation\Reservation;

/**
 * Displays the full reservations list in the admin panel.
 *
 * @package Controllers\Admin
 */
class AdminReservationsController implements ControllerInterface
{
    /**
     * @return void
     */
    public function control(): void
    {
        SessionGuard::requireAdmin();
        $reservations = Reservation::getAll();
        include __DIR__ . '/../../Views/Admin/reservations.php';
    }

    /**
     * @param string $path
     * @param string $method
     * @return bool
     */
    public static function support(string $path, string $method): bool
    {
        return $path === '/admin/reservations' && $method === 'GET';
    }
}