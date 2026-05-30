<?php

namespace Views\Base;

use Views\AbstractView;

/**
 * Renders the HTML <head> and top navigation bar.
 *
 * Automatically resolves the current path from the request URI
 * to highlight the active navigation link in the template.
 *
 * @package Views\Base
 */
class HeaderView extends AbstractView
{
    /** Template variable key — current URL path (used for active nav link) */
    public const CURRENT_PATH_KEY = 'CURRENT_PATH';

    /** Template variable key — HTML page title */
    public const PAGE_TITLE_KEY = 'PAGE_TITLE';

    /** @var string Absolute path to the header template */
    private const TEMPLATE_HTML = __DIR__ . '/header.php';

    /**
     * Resolves the current request path and injects default template values.
     */
    public function __construct()
    {
        $this->data[self::CURRENT_PATH_KEY] = parse_url(
            $_SERVER['REQUEST_URI'] ?? '/',
            PHP_URL_PATH
        ) ?? '/';

        $this->data[self::PAGE_TITLE_KEY] = 'Royal Autos — Automobiles de prestige · Montauban';
    }

    /**
     * @return string Absolute path to the header template
     */
    public function templatePath(): string
    {
        return self::TEMPLATE_HTML;
    }
}