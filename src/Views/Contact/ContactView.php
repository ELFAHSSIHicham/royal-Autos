<?php
namespace Views\Contact;

use Views\Base\BaseView;

class ContactView extends BaseView
{
    public function templatePath(): string
    {
        return __DIR__ . '/contact.php';
    }
}
