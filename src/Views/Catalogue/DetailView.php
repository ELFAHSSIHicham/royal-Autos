<?php

namespace Views\Catalogue;

use Views\Base\BaseView;

/**
 * View for the vehicle detail page.
 *
 * @package Views\Catalogue
 */
class DetailView extends BaseView
{
    /**
     * @return string Absolute path to the detail template
     */
    public function templatePath(): string
    {
        return __DIR__ . '/detail.php';
    }
}