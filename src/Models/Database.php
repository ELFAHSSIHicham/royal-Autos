<?php

namespace Models;

/**
 * Class Database
 *
 * Singleton MySQLi pour Royal Autos.
 * Lit les credentials depuis les variables d'environnement ou le fichier .env.
 *
 * @package Models
 */
class Database
{
    /**
     * Instance singleton de la connexion MySQLi
     *
     * @var \mysqli|null
     */
    private static ?\mysqli $conn = null;

    /**
     * Retourne la connexion active. La crée si elle n'existe pas encore.
     *
     * @return \mysqli
     * @throws \RuntimeException Si la connexion échoue
     */
    public static function getConnection(): \mysqli
    {
        if (self::$conn !== null) {
            return self::$conn;
        }

        $host = self::parseEnvVar('DB_HOST') ?: 'localhost';
        $user = self::parseEnvVar('DB_USER') ?: '';
        $pass = self::parseEnvVar('DB_PASSWORD') ?: '';
        $db   = self::parseEnvVar('DB_NAME') ?: '';

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
     * Lit une variable d'environnement depuis le système ou le fichier .env.
     * Cherche d'abord dans les variables système, puis dans .env.
     *
     * @param string $envVar Nom de la variable
     * @return string|false  Valeur ou false si introuvable
     */
    public static function parseEnvVar(string $envVar): string|false
    {
        // 1. Variables système en priorité
        $val = getenv($envVar);
        if ($val !== false && $val !== '') {
            return $val;
        }

        // 2. Lecture du fichier .env (à la racine du projet)
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

            if ($line === '' || $line[0] === '#' || $line[0] === ';') {
                continue;
            }
            if (!str_contains($line, '=')) {
                continue;
            }

            [$key, $value] = explode('=', $line, 2);
            $key   = trim($key);
            $value = trim($value);

            // Supprime les guillemets entourant la valeur
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
     * Vérifie que la connexion est active (utile pour les health checks).
     *
     * @throws \RuntimeException
     * @return void
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
