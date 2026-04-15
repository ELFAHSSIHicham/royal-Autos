<?php

return [
    '/' => ['App\\Controllers\\HomeController', 'index'],

    '/vehicles' => ['App\\Controllers\\VehicleController', 'index'],
    '/vehicle'  => ['App\\Controllers\\VehicleController', 'show'],

    '/admin/login'           => ['App\\Controllers\\AdminController', 'loginForm'],
    '/admin/login-post'      => ['App\\Controllers\\AdminController', 'loginPost'],
    '/admin/vehicles'        => ['App\\Controllers\\AdminController', 'vehiclesList'],
    '/admin/vehicles/create' => ['App\\Controllers\\AdminController', 'vehicleCreateForm'],
    '/admin/vehicles/store'  => ['App\\Controllers\\AdminController', 'vehicleStore'],
];
