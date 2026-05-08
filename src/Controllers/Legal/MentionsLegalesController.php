<?php
namespace Controllers\Legal;

use Controllers\ControllerInterface;
use Views\Legal\MentionsLegalesView;

class MentionsLegalesController implements ControllerInterface
{
    public const PATH = '/mentions-legales';

    public function control(): void
    {
        $view = new MentionsLegalesView();
        $view->setData([
            'PAGE_TITLE'   => 'Mentions légales — Royal Autos',
            'CURRENT_PATH' => self::PATH,
        ]);
        echo $view->render();
    }

    public static function support(string $path, string $method): bool
    {
        return $path === self::PATH && $method === 'GET';
    }
}
