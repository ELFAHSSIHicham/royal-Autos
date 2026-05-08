<?php
namespace Controllers\Reservation;

use Controllers\ControllerInterface;
use Shared\{CsrfGuard, RateLimiter, Sanitizer, InputValidator, StripeService, Mailer};
use Models\Voiture\Voiture;
use Models\Reservation\Reservation;

class ReservationPost implements ControllerInterface
{
    public function control(): void
    {
        CsrfGuard::check();

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
        $v->required('nom', $d['nom'], 'Nom')
          ->required('email', $d['email'], 'Email')
          ->email('email', $d['email'])
          ->required('telephone', $d['telephone'], 'Téléphone');

        if (!$v->isValid()) {
            $_SESSION['resa_errors'] = $v->getErrors();
            $_SESSION['resa_old']    = $_POST;
            header('Location: /reservation?slug=' . urlencode($slug));
            exit();
        }

        $acompte     = (int)round($voiture['prix'] * 0.10 * 100); // 10% en centimes
        $resaId      = Reservation::create([
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

    public static function support(string $path, string $method): bool
    {
        return $path === '/reservation' && $method === 'POST';
    }
}
