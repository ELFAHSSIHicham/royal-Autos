<?php

namespace Views\Base;

use Views\View;

/**
 * Base View
 *
 * Classe abstraite de base pour toutes les pages de Royal Autos.
 * Assemble la réponse HTML complète : header + corps + footer.
 *
 * Toutes les vues de pages concrètes étendent cette classe
 * et implémentent templatePath() pour indiquer leur template.
 *
 * @package Views\Base
 */
abstract class BaseView implements View
{
    /**
     * Données dynamiques fournies au template du corps (clé => valeur)
     *
     * @var array<string, mixed>
     */
    protected array $data = [];

    /**
     * Rend la page complète : header + corps + footer.
     *
     * @return string HTML complet
     */
    public function render(): string
    {
        $header = new HeaderView();
        $footer = new FooterView();

        return $header->renderBody()
            . $this->renderBody()
            . $footer->renderBody();
    }

    /**
     * Injecte des données pour le template du corps.
     *
     * @param array<string, mixed> $data
     * @return void
     */
    public function setData(array $data): void
    {
        $this->data = array_merge($this->data, $data);
    }

    /**
     * Rend le corps de la page à partir du template.
     *
     * @return string HTML du corps
     */
    public function renderBody(): string
    {
        $templatePath = $this->templatePath();

        ob_start();
        extract($this->data);
        include $templatePath;
        return (string) ob_get_clean();
    }

    /**
     * Convertit une valeur mixte en string de façon sûre.
     * Retourne une chaîne vide si la valeur n'est pas scalaire.
     *
     * @param mixed $value
     * @return string
     */
    protected function safeString(mixed $value): string
    {
        if ($value === null) {
            return '';
        }
        if (is_scalar($value) || $value instanceof \Stringable) {
            return (string) $value;
        }
        return '';
    }
}
