<?php

/**
 * Configuration base de données — Royal Autos
 *
 * Retourne l'instance de connexion MySQLi.
 * Les credentials sont lus depuis le fichier .env via Database::parseEnvVar().
 *
 * Usage :
 *   $db = require __DIR__ . '/../config/database.php';
 *
 * @package Config
 */

use Models\Database;

return Database::getConnection();
