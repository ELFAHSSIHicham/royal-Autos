<?php

return [
    // Public
    'home'               => '/',
    'catalogue'          => '/catalogue',
    'voiture_detail'     => '/voiture',
    'contact'            => '/contact',
    'mentions_legales'   => '/mentions-legales',
    'api_modeles'        => '/api/modeles',

    // Réservation
    'reservation'        => '/reservation',
    'stripe_success'     => '/reservation/succes',
    'stripe_cancel'      => '/reservation/annulee',
    'stripe_webhook'     => '/stripe/webhook',

    // Admin
    'admin_login'        => '/admin/login',
    'admin_logout'       => '/admin/logout',
    'admin_dashboard'    => '/admin',
    'admin_voitures'     => '/admin/voitures',
    'admin_voiture_new'  => '/admin/voitures/nouveau',
    'admin_voiture_edit' => '/admin/voitures/modifier',
    'admin_voiture_del'  => '/admin/voitures/supprimer',
    'admin_reservations' => '/admin/reservations',
];