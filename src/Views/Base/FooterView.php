<?php

namespace Views\Base;

use Views\AbstractView;

/**
 * Footer View
 *
 * Rend le footer Royal Autos avec coordonnées, liens de navigation
 * et mentions légales. Inclus automatiquement dans toutes les vues
 * qui étendent BaseView.
 *
 * @package Views\Base
 */
class FooterView extends AbstractView
{
    /** Clé template — année courante (copyright) */
    public const YEAR_KEY = 'YEAR';

    /** Chemin vers le template footer */
    private const TEMPLATE_HTML = __DIR__ . '/footer.php';

    /**
     * Constructeur — injecte les valeurs par défaut.
     */
    public function __construct()
    {
        $this->data[self::YEAR_KEY] = (string) date('Y');
    }

    /**
     * Retourne le chemin vers le template footer.
     *
     * @return string
     */
    public function templatePath(): string
    {
        return self::TEMPLATE_HTML;
    }
}
