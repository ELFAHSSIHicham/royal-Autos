<?php

namespace Views;

/**
 * Abstract View
 *
 * Classe de base pour tous les composants de vue de Royal Autos.
 * Fournit le mécanisme de rendu par template PHP avec injection de données.
 *
 * Système de templates :
 * - Les données sont stockées dans $data (clé => valeur)
 * - extract() rend chaque clé disponible comme variable dans le template
 * - Le rendu est capturé avec ob_start / ob_get_clean
 *
 * @package Views
 */
abstract class AbstractView implements View
{
    /**
     * Données dynamiques fournies au template (clé => valeur)
     * Exemple : ['token' => 'abc123', 'titre' => 'Royal Autos']
     *
     * @var array<string, mixed>
     */
    protected array $data = [];

    /**
     * Injecte des données pour le template.
     * Fusionne avec les données existantes sans écraser le tableau entier.
     *
     * @param array<string, mixed> $data
     * @return void
     */
    public function setData(array $data): void
    {
        $this->data = array_merge($this->data, $data);
    }

    /**
     * Rend le corps de la vue à partir du template.
     *
     * @return string HTML rendu
     */
    public function renderBody(): string
    {
        $templatePath = $this->templatePath();

        ob_start();
        extract($this->data);
        include $templatePath;
        return (string) ob_get_clean();
    }
}
