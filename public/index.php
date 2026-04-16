<?php

/**
 * Application Entry Point — Front Controller
 *
 * Initialises the environment, registers all controllers and routes
 * incoming HTTP requests to the appropriate controller.
 *
 * Routing: each controller implements support(path, method).
 * The router iterates the list and executes the first match.
 *
 * @package Root
 */

date_default_timezone_set(getenv('APP_TIMEZONE') ?: 'Europe/Paris');

//Autoloader
require_once __DIR__ . '/../Autoloader.php';

//OWASP Security Headers
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('X-XSS-Protection: 1; mode=block');
header('Referrer-Policy: strict-origin-when-cross-origin');
header('Permissions-Policy: camera=(), microphone=(), geolocation=()');
header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline' https://js.stripe.com; style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; font-src 'self' https://fonts.gstatic.com; img-src 'self' data:; frame-src https://js.stripe.com;");

//Session sécurisée
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

//Erreurs (dev uniquement)
if (
    isset($_SERVER['HTTP_HOST']) &&
    (
        str_contains($_SERVER['HTTP_HOST'], 'localhost') ||
        str_contains($_SERVER['HTTP_HOST'], '127.0.0.1')
    )
) {
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);
}

//Imports
use Controllers\Home\HomeController;

/**
 * Controllers Registry
 *
 * Tous les controllers de l'application sont enregistrés ici.
 * Premier match = exécution.
 *
 * @var array<int, \Controllers\ControllerInterface>
 */
$controllers = [
    new HomeController(),
    // S3 — Catalogue\CatalogueController, Catalogue\DetailVoitureController
    // S4 — User\LoginController, User\LoginPost, User\LogoutController
    // S5 — Admin\AdminDashboardController, Admin\AdminVoiture*
    // S6 — Reservation\ReservationController, Reservation\ReservationPost, Reservation\StripeWebhookController
    // S7 — Contact\ContactController, Contact\ContactPost, Legal\MentionsLegalesController
];

//Router
$path   = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/';
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

foreach ($controllers as $controller) {
    if ($controller::support($path, $method)) {
        error_log(sprintf('[RoyalAutos] Controller: %s', $controller::class));
        $controller->control();
        exit();
    }
}

//404 Fallback
http_response_code(404);
$home = new HomeController();
$home->control();
exit();
