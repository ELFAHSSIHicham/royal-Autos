<?php

namespace Controllers;

/**
 * Contract for all controllers in the application.
 *
 * @package Controllers
 */
interface ControllerInterface
{
    /**
     * Executes the controller logic (auth check, data fetch, view render or redirect).
     *
     * @return void
     */
    public function control(): void;

    /**
     * Returns true if this controller handles the given path and HTTP method.
     *
     * @param string $path   Request path
     * @param string $method HTTP method
     * @return bool
     */
    public static function support(string $path, string $method): bool;
}