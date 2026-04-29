<?php

/**
 * Front Controller — Royal Autos
 */

date_default_timezone_set(getenv('APP_TIMEZONE') ?: 'Europe/Paris');

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

// OWASP Security Headers
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('X-XSS-Protection: 1; mode=block');
header('Referrer-Policy: strict-origin-when-cross-origin');
header('Permissions-Policy: camera=(), microphone=(), geolocation=()');
header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline' https://js.stripe.com; style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; font-src 'self' https://fonts.gstatic.com; img-src 'self' data:; frame-src https://js.stripe.com;");

// Session sécurisée
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

// Mode debug local uniquement
if (
    isset($_SERVER['HTTP_HOST']) &&
    (str_contains($_SERVER['HTTP_HOST'], 'localhost') ||
     str_contains($_SERVER['HTTP_HOST'], '127.0.0.1'))
) {
    ini_set('display_errors', '1');
    error_reporting(E_ALL);
}

// ── Imports ──────────────────────────────────────────────────────────────────
use Controllers\Home\HomeController;
use Controllers\Catalogue\CatalogueController;
use Controllers\Catalogue\DetailVoitureController;
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
use Controllers\Catalogue\ModelesByMarqueController;
use Controllers\Admin\AdminImmatController;

// ── Stripe webhook (doit lire php://input AVANT session) ─────────────────────
$path   = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/';
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

if ($path === '/stripe/webhook' && $method === 'POST') {
    (new StripeWebhookController())->control();
    exit();
}

// ── Succès / annulation Stripe (pages simples sans BaseView) ─────────────────
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

// ── Uploads (proxy simple pour storage/uploads) ───────────────────────────────
if (str_starts_with($path, '/uploads/')) {
    $file = __DIR__ . '/../storage/uploads/' . basename($path);
    if (file_exists($file)) {
        $mime = mime_content_type($file) ?: 'application/octet-stream';
        header('Content-Type: ' . $mime);
        readfile($file);
        exit();
    }
}

// ── Registry des controllers ────────────────────────────────────��────────────
$controllers = [
    // Public
    new HomeController(),
    new CatalogueController(),
    new DetailVoitureController(),
    new ModelesByMarqueController(),
    new ContactController(),
    new ContactPost(),
    new MentionsLegalesController(),
    // Réservation
    new ReservationController(),
    new ReservationPost(),
    // Auth admin
    new LoginController(),
    new LoginPost(),
    new LogoutController(),
    // Admin
    new AdminDashboardController(),
    new AdminVoitureListController(),
    new AdminVoitureCreateController(),
    new AdminVoitureCreatePost(),
    new AdminVoitureEditController(),
    new AdminVoitureEditPost(),
    new AdminVoitureDeleteController(),
    new AdminReservationsController(),
    new AdminImmatController(),
];

// ── Router ───────────────────────────────────────────────────────────────────
foreach ($controllers as $controller) {
    if ($controller::support($path, $method)) {
        error_log(sprintf('[RoyalAutos] %s %s → %s', $method, $path, $controller::class));
        $controller->control();
        exit();
    }
}

// ── 404 Fallback ─────────────────────────────────────────────────────────────
http_response_code(404);
$PAGE_TITLE    = 'Page introuvable — Royal Autos';
$CURRENT_PATH  = $path;
include __DIR__ . '/../src/Views/Base/header.php';
include __DIR__ . '/../src/Views/Errors/404.php';
include __DIR__ . '/../src/Views/Base/footer.php';
exit();
