<?php

/**
 * PSR-4 compatible autoloader.
 *
 * Maps namespaces to the src/ directory by replacing namespace separators
 * with directory separators.
 *
 * Example:
 *   new Controllers\Home\HomeController()
 *   → loads src/Controllers/Home/HomeController.php
 */
class Autoloader
{
    /**
     * Registers the autoload callback with PHP's SPL autoloader stack.
     * Must be called once at application bootstrap.
     *
     * @return void
     */
    public static function register(): void
    {
        spl_autoload_register(function (string $class) {
            $file = __DIR__ . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR
                . str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';

            if (file_exists($file)) {
                require $file;
                return true;
            }
            return false;
        });
    }
}

Autoloader::register();