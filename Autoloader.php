<?php

/**
 * Autoloader Class
 *
 * PSR-4 compatible autoloading for Royal Autos.
 * Converts namespace separators to directory separators
 * and loads the corresponding file from the src/ directory.
 *
 * Example:
 *   new Controllers\Home\HomeController()
 *   → loads src/Controllers/Home/HomeController.php
 *
 * @package Root
 */
class Autoloader
{
    /**
     * Registers the autoload function with PHP's SPL autoloader.
     * Call once at application startup in public/index.php.
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
