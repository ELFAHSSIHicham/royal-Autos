<?php

namespace Views\Base;

use Views\AbstractView;

/**
 * Renders the site footer.
 *
 * Included automatically in all pages extending BaseView.
 * Injects the current year for the copyright notice.
 *
 * @package Views\Base
 */
class FooterView extends AbstractView
{
    /** Template variable key — current year for the copyright line */
    public const YEAR_KEY = 'YEAR';

    /** @var string Absolute path to the footer template */
    private const TEMPLATE_HTML = __DIR__ . '/footer.php';

    /**
     * Injects the current year into the template.
     */
    public function __construct()
    {
        $this->data[self::YEAR_KEY] = (string) date('Y');
    }

    /**
     * @return string Absolute path to the footer template
     */
    public function templatePath(): string
    {
        return self::TEMPLATE_HTML;
    }
}