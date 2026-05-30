<?php

namespace Controllers\Home;

use Controllers\ControllerInterface;
use Models\Voiture\Voiture;
use Views\Home\HomeView;

/**
 * Handles the home page.
 *
 * @package Controllers\Home
 */
class HomeController implements ControllerInterface
{
    /** @var string Route handled by this controller */
    public const PATH = '/';

    /**
     * @return void
     */
    public function control(): void
    {
        $view = new HomeView();
        $view->setData([
            'PAGE_TITLE'   => 'Royal Autos — Automobiles de prestige · Montauban',
            'CURRENT_PATH' => self::PATH,
            'vedettes'     => Voiture::getVedettes(3),
            'marques'      => Voiture::getMarques(),
            'marquesStrip' => Voiture::getMarquesAvecVoitures(),
            'nbVoitures'   => Voiture::countDisponibles(),
        ]);
        echo $view->render();
    }

    /**
     * @param string $path
     * @param string $method
     * @return bool
     */
    public static function support(string $path, string $method): bool
    {
        return $path === self::PATH && $method === 'GET';
    }
}