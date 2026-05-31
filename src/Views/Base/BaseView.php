<?php

namespace Views\Base;

use Views\View;

/**
 * Base page view.
 *
 * Assembles a full HTML response by wrapping the page body
 * between the shared header and footer partials.
 *
 * All concrete page views extend this class and implement
 * templatePath() to point to their specific template file.
 *
 * @package Views\Base
 */
abstract class BaseView implements View
{
    /**
     * Key-value pairs extracted as variables inside the body template.
     *
     * @var array<string, mixed>
     */
    protected array $data = [];

    /**
     * Renders the full page: header + body + footer.
     *
     * @return string Complete HTML document
     */
    public function render(): string
    {
        $header = new HeaderView();
        $footer = new FooterView();

        return $header->renderBody()
            . $this->renderBody()
            . $footer->renderBody();
    }

    /**
     * Merges the given data into the existing template data.
     *
     * @param array<string, mixed> $data
     * @return void
     */
    public function setData(array $data): void
    {
        $this->data = array_merge($this->data, $data);
    }

    /**
     * Renders the body template using output buffering.
     * Each key in $data becomes a local variable inside the template.
     *
     * @return string Rendered HTML body
     */
    public function renderBody(): string
    {
        $templatePath = $this->templatePath();

        ob_start();
        extract($this->data);
        include $templatePath;
        return (string) ob_get_clean();
    }

    /**
     * Safely casts a mixed value to string.
     * Returns an empty string for non-scalar, non-Stringable values.
     *
     * @param mixed $value
     * @return string
     */
    protected function safeString(mixed $value): string
    {
        if ($value === null) {
            return '';
        }
        if (is_scalar($value) || $value instanceof \Stringable) {
            return (string) $value;
        }
        return '';
    }
}