<?php
namespace Shared;

use Stripe\Stripe;
use Stripe\Checkout\Session;
use Stripe\Webhook;

class StripeService
{
    public function __construct()
    {
        Stripe::setApiKey(\Models\Database::parseEnvVar('STRIPE_SECRET_KEY') ?: '');
    }

    public function createCheckoutSession(
        int    $voitureId,
        string $label,
        int    $montantCentimes,
        string $successUrl,
        string $cancelUrl,
        string $clientEmail = ''
    ): Session {
        $params = [
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency'     => 'eur',
                    'unit_amount'  => $montantCentimes,
                    'product_data' => ['name' => 'Acompte – ' . $label],
                ],
                'quantity' => 1,
            ]],
            'mode'        => 'payment',
            'success_url' => $successUrl . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url'  => $cancelUrl,
            'metadata'    => ['voiture_id' => $voitureId],
        ];
        if ($clientEmail) {
            $params['customer_email'] = $clientEmail;
        }
        return Session::create($params);
    }

    public function constructEvent(string $payload, string $sig): \Stripe\Event
    {
        $secret = \Models\Database::parseEnvVar('STRIPE_WEBHOOK_SECRET') ?: '';
        return Webhook::constructEvent($payload, $sig, $secret);
    }
}
