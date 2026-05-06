<?php
namespace Controllers\Contact;

use Controllers\ControllerInterface;
use Shared\{CsrfGuard, RateLimiter, Sanitizer, InputValidator, Mailer};
use Models\Contact\ContactMessage;

class ContactPost implements ControllerInterface
{
    public function control(): void
    {
        CsrfGuard::check();

        if (!RateLimiter::check('contact', 3, 300)) {
            http_response_code(429);
            echo 'Trop de messages, réessayez plus tard.';
            exit();
        }

        $d = [
            'nom'       => Sanitizer::str($_POST['nom']       ?? ''),
            'email'     => Sanitizer::email($_POST['email']   ?? ''),
            'telephone' => Sanitizer::str($_POST['telephone'] ?? ''),
            'sujet'     => Sanitizer::str($_POST['sujet']     ?? ''),
            'message'   => Sanitizer::str($_POST['message']   ?? ''),
        ];

        $v = new InputValidator();
        $v->required('nom', $d['nom'], 'Nom')
          ->required('email', $d['email'], 'Email')
          ->email('email', $d['email'])
          ->required('message', $d['message'], 'Message')
          ->minLength('message', $d['message'], 10);

        if (!$v->isValid()) {
            $_SESSION['contact_errors'] = $v->getErrors();
            $_SESSION['contact_old']    = $_POST;
            header('Location: /contact');
            exit();
        }

        ContactMessage::create($d);

        // Email de confirmation au visiteur
        $mailer = new Mailer();
        ob_start();
        extract(['nom' => $d['nom'], 'message' => $d['message']]);
        include __DIR__ . '/../../Views/Emails/contact-confirmation.php';
        $html = ob_get_clean();
        $mailer->send($d['email'], $d['nom'], 'Votre message a bien été reçu — Royal Autos', $html);

        $_SESSION['contact_success'] = true;
        header('Location: /contact');
        exit();
    }

    public static function support(string $path, string $method): bool
    {
        return $path === '/contact' && $method === 'POST';
    }
}
