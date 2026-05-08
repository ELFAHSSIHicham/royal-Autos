<?php
namespace Models\Reservation;

use Models\Database;

class Reservation
{
    public static function create(array $d): int
    {
        $db  = Database::getConnection();
        $stmt = $db->prepare('SELECT id FROM clients WHERE email = ?');
        $stmt->bind_param('s', $d['email']);
        $stmt->execute();
        $row  = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if ($row) {
            $clientId = (int)$row['id'];
        } else {
            $stmt = $db->prepare('INSERT INTO clients (nom, prenom, email, telephone) VALUES (?,?,?,?)');
            $nom  = $d['nom']; $prenom = $d['prenom'] ?? '';
            $stmt->bind_param('ssss', $nom, $prenom, $d['email'], $d['telephone']);
            $stmt->execute();
            $clientId = (int)$db->insert_id;
            $stmt->close();
        }

        $ref    = 'RA-' . date('Y') . '-' . str_pad((string)rand(1, 999999), 6, '0', STR_PAD_LEFT);
        $stmt   = $db->prepare(
            'INSERT INTO reservations (reference,voiture_id,client_id,montant,statut,notes)
             VALUES (?,?,?,?,?,?)'
        );
        $statut = 'en_attente';
        $stmt->bind_param('siidss',
            $ref, $d['voiture_id'], $clientId,
            $d['montant'], $statut, $d['notes']
        );
        $stmt->execute();
        $id = (int)$db->insert_id;
        $stmt->close();
        return $id;
    }

    public static function getById(int $id): ?array
    {
        $db   = Database::getConnection();
        $stmt = $db->prepare(
            'SELECT r.*, c.nom, c.prenom, c.email, c.telephone,
                    ma.nom AS marque, v.modele, v.annee, v.slug
             FROM   reservations r
             JOIN   clients  c  ON c.id  = r.client_id
             JOIN   voitures v  ON v.id  = r.voiture_id
             JOIN   marques  ma ON ma.id = v.marque_id
             WHERE  r.id = ?'
        );
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $row  = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $row ?: null;
    }

    public static function getAll(): array
    {
        $db  = Database::getConnection();
        $res = $db->query(
            'SELECT r.*, c.nom, c.prenom, c.email, c.telephone,
                    ma.nom AS marque, v.modele, v.annee
             FROM   reservations r
             JOIN   clients  c  ON c.id  = r.client_id
             JOIN   voitures v  ON v.id  = r.voiture_id
             JOIN   marques  ma ON ma.id = v.marque_id
             ORDER BY r.created_at DESC'
        );
        return $res->fetch_all(MYSQLI_ASSOC);
    }

    public static function updateStripe(int $id, string $sessionId, string $paymentId, string $statut): void
    {
        $db   = Database::getConnection();
        $stmt = $db->prepare(
            'UPDATE reservations SET stripe_session_id=?, stripe_payment_id=?, statut=? WHERE id=?'
        );
        $stmt->bind_param('sssi', $sessionId, $paymentId, $statut, $id);
        $stmt->execute();
        $stmt->close();
    }

    public static function updateStatut(int $id, string $statut): void
    {
        $db   = Database::getConnection();
        $stmt = $db->prepare('UPDATE reservations SET statut=? WHERE id=?');
        $stmt->bind_param('si', $statut, $id);
        $stmt->execute();
        $stmt->close();
    }

    public static function getBySessionId(string $sessionId): ?array
    {
        $db   = Database::getConnection();
        $stmt = $db->prepare('SELECT * FROM reservations WHERE stripe_session_id = ?');
        $stmt->bind_param('s', $sessionId);
        $stmt->execute();
        $row  = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $row ?: null;
    }

    public static function countByStatut(string $statut): int
    {
        $db   = Database::getConnection();
        $stmt = $db->prepare('SELECT COUNT(*) AS c FROM reservations WHERE statut = ?');
        $stmt->bind_param('s', $statut);
        $stmt->execute();
        $row  = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return (int)$row['c'];
    }
}