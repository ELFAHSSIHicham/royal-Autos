<?php

namespace Controllers\Home;

use Controllers\ControllerInterface;

/**
 * Home Controller
 *
 * Affiche la page d'accueil de Royal Autos.
 *
 * @package Controllers\Home
 */
class HomeController implements ControllerInterface
{
    public const PATH = '/';

    public function control(): void
    {
        echo '<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Royal Autos — Automobiles de prestige · Montauban</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            background: #d3d3d3;
        }
        .card {
            background: #fff;
            padding: 3rem 4rem;
            text-align: center;
        }
        h1 {
            font-size: 2.2rem;
            letter-spacing: .14em;
            font-weight: 400;
            margin-bottom: 0.4rem;
        }
        span { color: #c9a84c; }
        p { color: #aaa; font-size: 0.78rem; letter-spacing: .2em; text-transform: uppercase; }
    </style>
</head>
<body>
    <div class="card">
        <h1>ROYAL <span>AUTOS</span></h1>
        <p>Automobiles de prestige · Montauban</p>
    </div>
</body>
</html>';
    }

    public static function support(string $path, string $method): bool
    {
        return $path === self::PATH && $method === 'GET';
    }
}
