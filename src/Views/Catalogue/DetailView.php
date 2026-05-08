<?php
namespace Views\Catalogue;

use Views\Base\BaseView;

class DetailView extends BaseView
{
    public function templatePath(): string
    {
        return __DIR__ . '/detail.php';
    }
}
