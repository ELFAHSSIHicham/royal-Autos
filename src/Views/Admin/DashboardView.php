<?php
namespace Views\Admin;

use Views\Base\BaseView;

class DashboardView extends BaseView
{
    public function templatePath(): string
    {
        return __DIR__ . '/dashboard.php';
    }
}
