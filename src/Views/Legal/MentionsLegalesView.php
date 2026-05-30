<?php

namespace Views\Legal;

use Views\Base\BaseView;

/**
 * View for the legal information page.
 *
 * Renders the Mentions légales, Confidentialité and CGV sections
 * through a tab-based interface driven by the URL hash.
 *
 * @package Views\Legal
 */
class MentionsLegalesView extends BaseView
{
    /**
     * @return string Absolute path to the legal page template
     */
    public function templatePath(): string
    {
        return __DIR__ . '/mentions-legales.php';
    }
}