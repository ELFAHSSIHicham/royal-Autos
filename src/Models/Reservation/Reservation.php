<?php

namespace Models\Reservation;

use Models\Database;

/**
 * Handles reservation persistence and retrieval.
 * Manages client upsert and Stripe session tracking.
 *
 * @package Models\Reservation
 */
class Reservation
{
    /**
     * Creates or reuses a client record, then inserts a new reservation.
     *
     * @param array<string, mixed> $d
     * @return int New reservation ID
     */
    public static function create(array $d): int
    {
        $db = Database::getConnection();

        /* Recherche d'un client existant par email pour éviter les doublons */
        $stmt = $db->prepare('SELECT id FROM clients WHERE email = ?');
        $stmt->bind_param('s', $d['email']);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if ($row) {
            $clientId = (int)$row['id'];
        } else {
            $stmt   = $db->prepare('INSERT INTO clients (nom, prenom, email, telephone) VALUES (?,?,?,?)');
            $nom    = $d['nom'];
            $prenom = $d['prenom'] ?? '';
            $stmt->bind_param('ssss', $nom, $prenom, $d['email'], $d['telephone']);
            $stmt->execute();
            $clientId = (int)$db->insert_id;
            $stmt->close();
        }

        /* Référence unique au format RA-YYYY-XXXXXX */
        $ref    = 'RA-' . date('Y') . '-' . str_pad((string)rand(1, 999999), 6, '0', STR_PAD_LEFT);
        $statut = 'en_attente';

        $stmt = $db->prepare(
            'INSERT INTO reservations (reference, voiture_id, client_id, montant, statut, notes)
             VALUES (?,?,?,?,?,?)'
        );
        $stmt->bind_param('siidss', $ref, $d['voiture_id'], $clientId, $d['montant'], $statut, $d['notes']);
        $stmt->execute();
        $id = (int)$db->insert_id;
        $stmt->close();

        return $id;
    }

    /**
     * Returns a single reservation with client and vehicle details.
     *
     * @param int $id
     * @return array<string, mixed>|null
     */
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
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $row ?: null;
    }

    /**
     * Returns all reservations with client and vehicle details, ordered by date.
     *
     * @return array<int, array<string, mixed>>
     */
    public static function getAll(): array
    {
        $res = Database::getConnection()->query(
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

    /**
     * Updates Stripe identifiers and payment status on a reservation.
     *
     * @param int    $id
     * @param string $sessionId
     * @param string $paymentId
     * @param string $statut
     * @return void
     */
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

    /**
     * Updates only the status of a reservation.
     *
     * @param int    $id
     * @param string $statut
     * @return void
     */
    public static function updateStatut(int $id, string $statut): void
    {
        $db   = Database::getConnection();
        $stmt = $db->prepare('UPDATE reservations SET statut=? WHERE id=?');
        $stmt->bind_param('si', $statut, $id);
        $stmt->execute();
        $stmt->close();
    }

    /**
     * Finds a reservation by its Stripe checkout session ID.
     *
     * @param string $sessionId
     * @return array<string, mixed>|null
     */
    public static function getBySessionId(string $sessionId): ?array
    {
        $db   = Database::getConnection();
        $stmt = $db->prepare('SELECT * FROM reservations WHERE stripe_session_id = ?');
        $stmt->bind_param('s', $sessionId);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $row ?: null;
    }

    /**
     * Counts reservations matching a given status.
     *
     * @param string $statut
     * @return int
     */
    public static function countByStatut(string $statut): int
    {
        $db   = Database::getConnection();
        $stmt = $db->prepare('SELECT COUNT(*) AS c FROM reservations WHERE statut = ?');
        $stmt->bind_param('s', $statut);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return (int)$row['c'];
    }
}