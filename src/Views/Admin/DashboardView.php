<?php

namespace Views\Admin;

use Views\Base\BaseView;

/**
 * View for the admin dashboard page.
 *
 * @package Views\Admin
 */
class DashboardView extends BaseView
{
    /**
     * @return string Absolute path to the dashboard template
     */
    public function templatePath(): string
    {
        return __DIR__ . '/dashboard.php';
    }
}