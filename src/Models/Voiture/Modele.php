<?php
namespace Models\Voiture;

use Models\Database;

class Modele
{
    public static function getByMarque(int $marqueId): array
    {
        $db   = Database::getConnection();   // ← getConnection(), pas getInstance()
        $stmt = $db->prepare(
            'SELECT id, nom FROM modeles
              WHERE marque_id = ? AND actif = 1
              ORDER BY nom ASC'
        );
        $stmt->bind_param('i', $marqueId);   // ← MySQLi : bind_param, pas execute([])
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}