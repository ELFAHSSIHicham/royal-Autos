<?php

namespace Models;

/**
 * MySQLi singleton connection manager.
 * Reads credentials from environment variables or the .env file.
 *
 * @package Models
 */
class Database
{
    /** @var \mysqli|null Active MySQLi connection instance */
    private static ?\mysqli $conn = null;

    /**
     * Returns the active connection, creating it on first call.
     *
     * @return \mysqli
     * @throws \RuntimeException If the connection fails
     */
    public static function getConnection(): \mysqli
    {
        if (self::$conn !== null) {
            return self::$conn;
        }

        $host = self::parseEnvVar('DB_HOST')     ?: 'localhost';
        $user = self::parseEnvVar('DB_USER')     ?: '';
        $pass = self::parseEnvVar('DB_PASSWORD') ?: '';
        $db   = self::parseEnvVar('DB_NAME')     ?: '';

        try {
            mysqli_report(MYSQLI_REPORT_STRICT | MYSQLI_REPORT_ERROR);
            self::$conn = new \mysqli($host, $user, $pass, $db);
            self::$conn->set_charset('utf8mb4');
        } catch (\mysqli_sql_exception $e) {
            throw new \RuntimeException(
                'Impossible de se connecter à la base de données. ' .
                'Veuillez contacter contact@royalautos.fr.'
            );
        }

        return self::$conn;
    }

    /**
     * Reads an environment variable from the system or the .env file.
     * System variables take priority over the .env file.
     *
     * @param string $envVar Variable name
     * @return string|false  Value or false if not found
     */
    public static function parseEnvVar(string $envVar): string|false
    {
        /* Variables système en priorité */
        $val = getenv($envVar);
        if ($val !== false && $val !== '') {
            return $val;
        }

        $envPath = __DIR__ . '/../../.env';
        if (!file_exists($envPath)) {
            return false;
        }

        $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if ($lines === false) {
            return false;
        }

        foreach ($lines as $line) {
            $line = trim($line);

            /* Ignorer les commentaires et les lignes sans séparateur */
            if ($line === '' || $line[0] === '#' || $line[0] === ';' || !str_contains($line, '=')) {
                continue;
            }

            [$key, $value] = explode('=', $line, 2);
            $key   = trim($key);
            $value = trim($value);

            /* Suppression des guillemets simples ou doubles autour de la valeur */
            if (
                (str_starts_with($value, '"') && str_ends_with($value, '"')) ||
                (str_starts_with($value, "'") && str_ends_with($value, "'"))
            ) {
                $value = substr($value, 1, -1);
            }

            if ($key === $envVar) {
                return $value;
            }
        }

        return false;
    }

    /**
     * Pings the database to verify the connection is alive.
     *
     * @return void
     * @throws \RuntimeException
     */
    public static function checkConnection(): void
    {
        try {
            $db = self::getConnection();
            if (!$db->ping()) {
                throw new \RuntimeException('La connexion à la base de données ne répond pas.');
            }
        } catch (\Exception $e) {
            throw new \RuntimeException('Impossible de joindre la base de données.');
        }
    }
}