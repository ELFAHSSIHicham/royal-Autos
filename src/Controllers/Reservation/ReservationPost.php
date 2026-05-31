<?php

namespace Controllers\Reservation;

use Controllers\ControllerInterface;
use Shared\{CsrfGuard, RateLimiter, Sanitizer, InputValidator, StripeService};
use Models\Voiture\Voiture;
use Models\Reservation\Reservation;

/**
 * Processes the reservation form and initiates a Stripe checkout session.
 *
 * @package Controllers\Reservation
 */
class ReservationPost implements ControllerInterface
{
    /**
     * @return void
     */
    public function control(): void
    {
        CsrfGuard::check();

        /* 3 réservations max toutes les 5 minutes par IP */
        if (!RateLimiter::check('reservation', 3, 300)) {
            http_response_code(429);
            echo 'Trop de demandes, réessayez dans quelques minutes.';
            exit();
        }

        $slug    = Sanitizer::str($_POST['slug'] ?? '');
        $voiture = Voiture::getBySlug($slug);

        if (!$voiture) {
            header('Location: /catalogue');
            exit();
        }

        $d = [
            'nom'       => Sanitizer::str($_POST['nom']       ?? ''),
            'prenom'    => Sanitizer::str($_POST['prenom']    ?? ''),
            'email'     => Sanitizer::email($_POST['email']   ?? ''),
            'telephone' => Sanitizer::str($_POST['telephone'] ?? ''),
            'notes'     => Sanitizer::str($_POST['notes']     ?? ''),
        ];

        $v = new InputValidator();
        $v->required('nom',       $d['nom'],       'Nom')
            ->required('email',     $d['email'],     'Email')
            ->email('email',        $d['email'])
            ->required('telephone', $d['telephone'], 'Téléphone');

        if (!$v->isValid()) {
            $_SESSION['resa_errors'] = $v->getErrors();
            $_SESSION['resa_old']    = $_POST;
            header('Location: /reservation?slug=' . urlencode($slug));
            exit();
        }

        /* L'acompte correspond à 10% du prix, converti en centimes pour Stripe */
        $prix    = (float)($voiture['prix'] ?? 0);
        $acompte = intval($prix * 0.10 * 100);

        $resaId = Reservation::create([
            'voiture_id' => (int)$voiture['id'],
            'nom'        => $d['nom'],
            'prenom'     => $d['prenom'],
            'email'      => $d['email'],
            'telephone'  => $d['telephone'],
            'montant'    => $voiture['prix'],
            'notes'      => $d['notes'],
        ]);

        $appUrl  = \Models\Database::parseEnvVar('APP_URL') ?: 'http://localhost';
        $stripe  = new StripeService();
        $session = $stripe->createCheckoutSession(
            (int)$voiture['id'],
            $voiture['marque'] . ' ' . $voiture['modele'],
            $acompte,
            $appUrl . '/reservation/succes',
            $appUrl . '/reservation/annulee',
            $d['email']
        );

        Reservation::updateStripe($resaId, $session->id, '', 'en_attente');
        Voiture::setStatut((int)$voiture['id'], 'reserve');

        header('Location: ' . $session->url);
        exit();
    }

    /**
     * @param string $path
     * @param string $method
     * @return bool
     */
    public static function support(string $path, string $method): bool
    {
        return $path === '/reservation' && $method === 'POST';
    }
}