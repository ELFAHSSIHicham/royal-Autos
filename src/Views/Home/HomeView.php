<?php

namespace Views\Home;

use Views\Base\BaseView;

/**
 * View for the home page.
 *
 * @package Views\Home
 */
class HomeView extends BaseView
{
    /**
     * @return string Absolute path to the home page template
     */
    public function templatePath(): string
    {
        return __DIR__ . '/home.php';
    }
}