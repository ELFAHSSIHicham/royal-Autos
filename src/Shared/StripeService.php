<?php

namespace Shared;

use Stripe\Stripe;
use Stripe\Checkout\Session;
use Stripe\Webhook;

/**
 * Wraps the Stripe SDK for checkout session creation and webhook event verification.
 *
 * @package Shared
 */
class StripeService
{
    /**
     * Initializes the Stripe SDK with the secret key from the environment.
     */
    public function __construct()
    {
        Stripe::setApiKey(\Models\Database::parseEnvVar('STRIPE_SECRET_KEY') ?: '');
    }

    /**
     * Creates a Stripe Checkout session for a deposit payment.
     *
     * @param int    $voitureId       Vehicle ID stored in session metadata
     * @param string $label           Vehicle label shown on the Stripe payment page
     * @param int    $montantCentimes Amount in euro cents (e.g. 50000 = 500.00 €)
     * @param string $successUrl      Redirect URL on successful payment
     * @param string $cancelUrl       Redirect URL when the user cancels
     * @param string $clientEmail     Pre-fills the email field on the Stripe page (optional)
     * @return Session
     */
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
            'line_items'           => [[
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

        /* Pré-remplissage de l'email si fourni pour simplifier le parcours client */
        if ($clientEmail) {
            $params['customer_email'] = $clientEmail;
        }

        return Session::create($params);
    }

    /**
     * Verifies the Stripe webhook signature and returns the decoded event.
     * Throws an exception if the signature is invalid or the payload is malformed.
     *
     * @param string $payload Raw request body from php://input
     * @param string $sig     Value of the Stripe-Signature HTTP header
     * @return \Stripe\Event
     */
    public function constructEvent(string $payload, string $sig): \Stripe\Event
    {
        $secret = \Models\Database::parseEnvVar('STRIPE_WEBHOOK_SECRET') ?: '';
        return Webhook::constructEvent($payload, $sig, $secret);
    }
}