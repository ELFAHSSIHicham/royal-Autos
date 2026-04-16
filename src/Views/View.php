<?php

namespace Views;

/**
 * View Interface
 *
 * Contrat pour tous les composants de vue de Royal Autos.
 * Les vues sont responsables du rendu HTML à partir de templates PHP.
 *
 * @package Views
 */
interface View
{
    /**
     * Retourne le chemin absolu vers le fichier template PHP.
     *
     * @return string
     */
    public function templatePath(): string;

    /**
     * Rend le contenu HTML de la vue.
     * Utilise ob_start / ob_get_clean en interne.
     *
     * @return string HTML rendu
     */
    public function renderBody(): string;
}
