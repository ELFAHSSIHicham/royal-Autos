<?php
namespace Views\Catalogue;

use Views\Base\BaseView;

class CatalogueView extends BaseView
{
    public function templatePath(): string
    {
        return __DIR__ . '/catalogue.php';
    }
}
