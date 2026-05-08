<?php
namespace Controllers\Admin;

use Controllers\ControllerInterface;
use Shared\SessionGuard;
use Models\Reservation\Reservation;

class AdminReservationsController implements ControllerInterface
{
    public function control(): void
    {
        SessionGuard::requireAdmin();
        $reservations = Reservation::getAll();
        include __DIR__ . '/../../Views/Admin/reservations.php';
    }

    public static function support(string $path, string $method): bool
    {
        return $path === '/admin/reservations' && $method === 'GET';
    }
}
