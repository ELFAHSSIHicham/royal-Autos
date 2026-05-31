<?php

namespace Views\Catalogue;

use Views\Base\BaseView;

/**
 * View for the vehicle catalogue page.
 *
 * @package Views\Catalogue
 */
class CatalogueView extends BaseView
{
    /**
     * @return string Absolute path to the catalogue template
     */
    public function templatePath(): string
    {
        return __DIR__ . '/catalogue.php';
    }
}