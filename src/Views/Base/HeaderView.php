<?php

namespace Views\Base;

use Views\AbstractView;

/**
 * Header View
 *
 * Rend la navbar et le <head> HTML de Royal Autos.
 * Injecte automatiquement le chemin courant pour surligner le lien actif.
 *
 * @package Views\Base
 */
class HeaderView extends AbstractView
{
    /** Clé template — chemin courant (pour le lien actif dans la nav) */
    public const CURRENT_PATH_KEY = 'CURRENT_PATH';

    /** Clé template — titre de la page HTML */
    public const PAGE_TITLE_KEY = 'PAGE_TITLE';

    /** Chemin vers le template header */
    private const TEMPLATE_HTML = __DIR__ . '/header.php';

    /**
     * Constructeur — injecte les valeurs par défaut dans le template.
     */
    public function __construct()
    {
        $currentPath = parse_url(
            $_SERVER['REQUEST_URI'] ?? '/',
            PHP_URL_PATH
        ) ?? '/';

        $this->data[self::CURRENT_PATH_KEY] = $currentPath;
        $this->data[self::PAGE_TITLE_KEY]   = 'Royal Autos — Automobiles de prestige · Montauban';
    }

    /**
     * Retourne le chemin vers le template header.
     *
     * @return string
     */
    public function templatePath(): string
    {
        return self::TEMPLATE_HTML;
    }
}
