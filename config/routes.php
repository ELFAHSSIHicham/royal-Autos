<?php

/**
 * Routes — Royal Autos
 *
 * Référentiel centralisé de toutes les routes de l'application.
 * Les controllers utilisent ces constantes dans support() et dans les redirects
 * pour éviter les chaînes codées en dur.
 *
 * @package Config
 */

return [
    //Public
    'home'               => '/',
    'catalogue'          => '/catalogue',
    'voiture_detail'     => '/voiture',           // + /{slug}
    'contact'            => '/contact',
    'mentions_legales'   => '/mentions-legales',

    //Réservation
    'reservation'        => '/reservation',
    'stripe_success'     => '/reservation/succes',
    'stripe_cancel'      => '/reservation/annulee',
    'stripe_webhook'     => '/stripe/webhook',

    //Admin
    'admin_login'        => '/admin/login',
    'admin_logout'       => '/admin/logout',
    'admin_dashboard'    => '/admin',
    'admin_voitures'     => '/admin/voitures',
    'admin_voiture_new'  => '/admin/voitures/nouveau',
    'admin_voiture_edit' => '/admin/voitures/modifier',   // + /{id}
    'admin_voiture_del'  => '/admin/voitures/supprimer',  // + /{id}
    'admin_reservations' => '/admin/reservations',
];
