<?php

namespace Controllers\Reservation;

use Controllers\ControllerInterface;
use Shared\StripeService;
use Models\Reservation\Reservation;
use Models\Voiture\Voiture;

/**
 * Handles incoming Stripe webhook events.
 * Verifies the signature before processing any payload.
 *
 * @package Controllers\Reservation
 */
class StripeWebhookController implements ControllerInterface
{
    /**
     * @return void
     */
    public function control(): void
    {
        $payload = file_get_contents('php://input');
        $sig     = $_SERVER['HTTP_STRIPE_SIGNATURE'] ?? '';

        try {
            $stripe = new StripeService();
            $event  = $stripe->constructEvent($payload, $sig);
        } catch (\Exception $e) {
            http_response_code(400);
            echo 'Webhook error: ' . $e->getMessage();
            exit();
        }

        /* Paiement confirmé : on met à jour la réservation et le statut du véhicule */
        if ($event->type === 'checkout.session.completed') {
            $session = $event->data->object;
            $resa    = Reservation::getBySessionId($session->id);

            if ($resa) {
                Reservation::updateStripe(
                    (int)$resa['id'],
                    $session->id,
                    $session->payment_intent ?? '',
                    'payee'
                );
                Voiture::setStatut((int)$resa['voiture_id'], 'vendu');
            }
        }

        http_response_code(200);
        echo 'ok';
    }

    /**
     * @param string $path
     * @param string $method
     * @return bool
     */
    public static function support(string $path, string $method): bool
    {
        return $path === '/stripe/webhook' && $method === 'POST';
    }
}