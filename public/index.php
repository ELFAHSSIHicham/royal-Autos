<?php

/**
 * Application front controller.
 * Single entry point for all HTTP requests.
 * Handles environment loading, security headers, session setup and routing.
 */

date_default_timezone_set(getenv('APP_TIMEZONE') ?: 'Europe/Paris');

/* Chargement du fichier .env si les variables système ne sont pas déjà définies */
$envFile = __DIR__ . '/../.env';
if (file_exists($envFile)) {
    foreach (file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        if (str_starts_with(trim($line), '#') || !str_contains($line, '=')) continue;
        [$key, $val] = explode('=', $line, 2);
        $key = trim($key);
        $val = trim(trim($val), '"\'');
        putenv("$key=$val");
        $_ENV[$key] = $val;
    }
}

require_once __DIR__ . '/../Autoloader.php';

/* En-têtes de sécurité OWASP */
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('X-XSS-Protection: 1; mode=block');
header('Referrer-Policy: strict-origin-when-cross-origin');
header('Permissions-Policy: camera=(), microphone=(), geolocation=()');
header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline' https://js.stripe.com; style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; font-src 'self' https://fonts.gstatic.com; img-src 'self' data:; connect-src 'self' https://api.stripe.com; frame-src https://js.stripe.com;");

/* Configuration et démarrage de la session sécurisée */
if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'lifetime' => 0,
        'path'     => '/',
        'domain'   => '',
        'secure'   => isset($_SERVER['HTTPS']),
        'httponly' => true,
        'samesite' => 'Strict',
    ]);
    session_start();
}

/* Affichage des erreurs uniquement en environnement local */
if (
    isset($_SERVER['HTTP_HOST']) &&
    (str_contains($_SERVER['HTTP_HOST'], 'localhost') ||
        str_contains($_SERVER['HTTP_HOST'], '127.0.0.1'))
) {
    ini_set('display_errors', '1');
    error_reporting(E_ALL);
}

use Controllers\Home\HomeController;
use Controllers\Catalogue\CatalogueController;
use Controllers\Catalogue\DetailVoitureController;
use Controllers\Catalogue\ModelesByMarqueController;
use Controllers\Contact\ContactController;
use Controllers\Contact\ContactPost;
use Controllers\Legal\MentionsLegalesController;
use Controllers\Reservation\ReservationController;
use Controllers\Reservation\ReservationPost;
use Controllers\Reservation\StripeWebhookController;
use Controllers\User\LoginController;
use Controllers\User\LoginPost;
use Controllers\User\LogoutController;
use Controllers\Admin\AdminDashboardController;
use Controllers\Admin\AdminVoitureListController;
use Controllers\Admin\AdminVoitureCreateController;
use Controllers\Admin\AdminVoitureCreatePost;
use Controllers\Admin\AdminVoitureEditController;
use Controllers\Admin\AdminVoitureEditPost;
use Controllers\Admin\AdminVoitureDeleteController;
use Controllers\Admin\AdminReservationsController;
use Controllers\Admin\AdminImmatController;
use Controllers\Admin\AdminMarqueCreatePost;
use Controllers\Admin\AdminModeleCreatePost;

$path   = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/';
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

/* Le webhook Stripe doit être traité avant le démarrage de session
   car il lit php://input en raw et ne nécessite pas de cookie */
if ($path === '/stripe/webhook' && $method === 'POST') {
    (new StripeWebhookController())->control();
    exit();
}

/* Pages de retour Stripe : rendu direct sans passer par le système de vues */
if ($path === '/reservation/succes' && $method === 'GET') {
    $PAGE_TITLE = 'Réservation confirmée — Royal Autos';
    include __DIR__ . '/../src/Views/Base/header.php';
    include __DIR__ . '/../src/Views/Reservation/stripe-success.php';
    include __DIR__ . '/../src/Views/Base/footer.php';
    exit();
}
if ($path === '/reservation/annulee' && $method === 'GET') {
    $PAGE_TITLE = 'Paiement annulé — Royal Autos';
    include __DIR__ . '/../src/Views/Base/header.php';
    include __DIR__ . '/../src/Views/Reservation/stripe-cancel.php';
    include __DIR__ . '/../src/Views/Base/footer.php';
    exit();
}

/* Serveur de fichiers uploadés depuis storage/uploads/ */
if (str_starts_with($path, '/uploads/')) {
    $file = __DIR__ . '/../storage/uploads/' . basename($path);
    if (file_exists($file)) {
        $mime = mime_content_type($file) ?: 'application/octet-stream';
        header('Content-Type: ' . $mime);
        readfile($file);
        exit();
    }
}

/* Registre de tous les controllers — parcouru dans l'ordre jusqu'au premier match */
$controllers = [
    new HomeController(),
    new CatalogueController(),
    new DetailVoitureController(),
    new ModelesByMarqueController(),
    new ContactController(),
    new ContactPost(),
    new MentionsLegalesController(),
    new ReservationController(),
    new ReservationPost(),
    new LoginController(),
    new LoginPost(),
    new LogoutController(),
    new AdminDashboardController(),
    new AdminVoitureListController(),
    new AdminVoitureCreateController(),
    new AdminVoitureCreatePost(),
    new AdminVoitureEditController(),
    new AdminVoitureEditPost(),
    new AdminVoitureDeleteController(),
    new AdminReservationsController(),
    new AdminImmatController(),
    new AdminMarqueCreatePost(),
    new AdminModeleCreatePost(),
];

foreach ($controllers as $controller) {
    if ($controller::support($path, $method)) {
        error_log(sprintf('[RoyalAutos] %s %s → %s', $method, $path, $controller::class));
        $controller->control();
        exit();
    }
}

/* Aucun controller ne correspond à la route demandée */
http_response_code(404);
$PAGE_TITLE   = 'Page introuvable — Royal Autos';
$CURRENT_PATH = $path;
include __DIR__ . '/../src/Views/Base/header.php';
include __DIR__ . '/../src/Views/Errors/404.php';
include __DIR__ . '/../src/Views/Base/footer.php';
exit();