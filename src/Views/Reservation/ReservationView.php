<?php
namespace Views\Reservation;

use Views\Base\BaseView;

class ReservationView extends BaseView
{
    public function templatePath(): string
    {
        return __DIR__ . '/reservation-form.php';
    }
}
