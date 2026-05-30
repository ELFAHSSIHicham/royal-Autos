<?php

namespace Views;

/**
 * Contract for all view components in the application.
 *
 * Views are solely responsible for HTML rendering from PHP templates.
 * They must not contain business logic or database access.
 *
 * @package Views
 */
interface View
{
    /**
     * Returns the absolute path to the PHP template file.
     *
     * @return string
     */
    public function templatePath(): string;

    /**
     * Renders the HTML content of the view.
     * Internally uses output buffering (ob_start / ob_get_clean).
     *
     * @return string Rendered HTML
     */
    public function renderBody(): string;
}