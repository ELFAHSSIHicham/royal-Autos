<?php

namespace Models\Voiture;

use Models\Database;

/**
 * Handles vehicle model retrieval from the database.
 *
 * @package Models\Voiture
 */
class Modele
{
    /**
     * Returns all active models for a given brand, ordered alphabetically.
     *
     * @param int $marqueId
     * @return array<int, array<string, mixed>>
     */
    public static function getByMarque(int $marqueId): array
    {
        $db   = Database::getConnection();
        $stmt = $db->prepare(
            'SELECT id, nom FROM modeles WHERE marque_id = ? AND actif = 1 ORDER BY nom ASC'
        );
        $stmt->bind_param('i', $marqueId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}