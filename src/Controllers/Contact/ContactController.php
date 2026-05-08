<?php
namespace Controllers\Contact;

use Controllers\ControllerInterface;
use Views\Contact\ContactView;

class ContactController implements ControllerInterface
{
    public const PATH = '/contact';

    public function control(): void
    {
        $errors  = $_SESSION['contact_errors'] ?? [];
        $success = $_SESSION['contact_success'] ?? false;
        $old     = $_SESSION['contact_old']    ?? [];
        unset($_SESSION['contact_errors'], $_SESSION['contact_success'], $_SESSION['contact_old']);

        $view = new ContactView();
        $view->setData([
            'PAGE_TITLE'   => 'Contact — Royal Autos',
            'CURRENT_PATH' => self::PATH,
            'errors'       => $errors,
            'success'      => $success,
            'old'          => $old,
        ]);
        echo $view->render();
    }

    public static function support(string $path, string $method): bool
    {
        return $path === self::PATH && $method === 'GET';
    }
}
