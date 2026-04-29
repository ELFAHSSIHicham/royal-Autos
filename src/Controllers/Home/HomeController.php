<?php

namespace Controllers\Home;

use Controllers\ControllerInterface;
use Models\Voiture\Voiture;
use Views\Home\HomeView;

class HomeController implements ControllerInterface
{
    public const PATH = '/';

    public function control(): void
    {
        $vedettes    = Voiture::getVedettes(3);
        $marques     = Voiture::getMarques();
        $nbVoitures  = Voiture::countDisponibles();

        $view = new HomeView();
        $view->setData([
            'PAGE_TITLE'   => 'Royal Autos — Automobiles de prestige · Montauban',
            'CURRENT_PATH' => self::PATH,
            'vedettes'     => $vedettes,
            'marques'      => $marques,
            'nbVoitures'   => $nbVoitures,
        ]);
        echo $view->render();
    }

    public static function support(string $path, string $method): bool
    {
        return $path === self::PATH && $method === 'GET';
    }
}
