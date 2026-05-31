<?php

namespace Controllers\Contact;

use Controllers\ControllerInterface;
use Views\Contact\ContactView;

/**
 * Displays the contact form.
 *
 * @package Controllers\Contact
 */
class ContactController implements ControllerInterface
{
    /** @var string Route handled by this controller */
    public const PATH = '/contact';

    /**
     * @return void
     */
    public function control(): void
    {
        /* Récupération et suppression immédiate des données flash de session */
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

    /**
     * @param string $path
     * @param string $method
     * @return bool
     */
    public static function support(string $path, string $method): bool
    {
        return $path === self::PATH && $method === 'GET';
    }
}