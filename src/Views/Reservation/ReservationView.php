<?php

namespace Views\Reservation;

use Views\Base\BaseView;

/**
 * View for the vehicle reservation form page.
 *
 * @package Views\Reservation
 */
class ReservationView extends BaseView
{
    /**
     * @return string Absolute path to the reservation form template
     */
    public function templatePath(): string
    {
        return __DIR__ . '/reservation-form.php';
    }
}