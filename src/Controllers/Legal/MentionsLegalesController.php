<?php

namespace Controllers\Legal;

use Controllers\ControllerInterface;
use Views\Legal\MentionsLegalesView;

/**
 * Handles the legal information page.
 *
 * Serves GET /mentions-legales and renders all three legal sections
 * (Mentions légales, Confidentialité, CGV) within a single template.
 * Tab navigation is handled client-side via URL hash.
 *
 * @package Controllers\Legal
 */
class MentionsLegalesController implements ControllerInterface
{
    /** @var string Route handled by this controller */
    public const PATH = '/mentions-legales';

    /**
     * Renders the legal page and sends it to the output.
     *
     * @return void
     */
    public function control(): void
    {
        $view = new MentionsLegalesView();
        $view->setData([
            'PAGE_TITLE'   => 'Mentions légales — Royal Autos',
            'CURRENT_PATH' => self::PATH,
        ]);
        echo $view->render();
    }

    /**
     * Returns true if this controller can handle the given request.
     * Only accepts GET requests on the exact /mentions-legales path.
     *
     * @param string $path   Request path
     * @param string $method HTTP method
     * @return bool
     */
    public static function support(string $path, string $method): bool
    {
        return $path === self::PATH && $method === 'GET';
    }
}