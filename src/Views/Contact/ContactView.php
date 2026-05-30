<?php

namespace Views\Contact;

use Views\Base\BaseView;

/**
 * View for the contact form page.
 *
 * @package Views\Contact
 */
class ContactView extends BaseView
{
    /**
     * @return string Absolute path to the contact template
     */
    public function templatePath(): string
    {
        return __DIR__ . '/contact.php';
    }
}