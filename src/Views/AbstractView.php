<?php

namespace Views;

/**
 * Base class for all view components.
 *
 * Provides a template rendering engine using PHP's output buffering.
 * Data injected via setData() is extracted as local variables inside the template,
 * making each key directly accessible (e.g. $PAGE_TITLE, $CURRENT_PATH).
 *
 * @package Views
 */
abstract class AbstractView implements View
{
    /**
     * Key-value pairs extracted as variables inside the template.
     *
     * @var array<string, mixed>
     */
    protected array $data = [];

    /**
     * Merges the given data into the existing template data.
     * Does not overwrite previously set keys unless explicitly included.
     *
     * @param array<string, mixed> $data
     * @return void
     */
    public function setData(array $data): void
    {
        $this->data = array_merge($this->data, $data);
    }

    /**
     * Renders the template by extracting $data into local variables
     * and capturing the output buffer.
     *
     * @return string Rendered HTML
     */
    public function renderBody(): string
    {
        $templatePath = $this->templatePath();

        ob_start();
        extract($this->data);
        include $templatePath;
        return (string) ob_get_clean();
    }
}