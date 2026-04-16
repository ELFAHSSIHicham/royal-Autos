<?php

namespace Controllers;

/**
 * Controller Interface
 *
 * Contrat que tous les controllers de Royal Autos doivent implémenter.
 * Utilisé par le front controller (index.php) pour le routage.
 *
 * @package Controllers
 */
interface ControllerInterface
{
    /**
     * Exécute la logique principale du controller.
     * Authentification, récupération des données, rendu de la vue ou redirect.
     *
     * @return void
     */
    public function control(): void;

    /**
     * Indique si ce controller prend en charge la route et la méthode HTTP.
     *
     * @param string $path   Chemin de l'URL (ex: '/catalogue', '/admin')
     * @param string $method Méthode HTTP    (ex: 'GET', 'POST')
     * @return bool
     */
    public static function support(string $path, string $method): bool;
}
