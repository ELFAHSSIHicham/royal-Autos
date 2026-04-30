<?php
namespace Models\Voiture;

use Models\Database;

class Voiture
{
    // ── SELECT de base avec JOIN marques + modeles ────────────────────────────
    private static string $select = "
        SELECT  v.*,
                ma.nom        AS marque,
                mo.nom        AS nom_modele
        FROM    voitures v
        JOIN    marques  ma ON ma.id = v.marque_id
        LEFT JOIN modeles mo ON mo.id = v.modele_id
    ";

    // ── Lecture ──────────────────────────────────────────────────────────────

    public static function getAll(array $f = [], int $page = 1, int $perPage = 9): array
    {
        $db     = Database::getConnection();
        $where  = ["v.statut = 'disponible'"];
        $params = [];
        $types  = '';

        if (!empty($f['marque_id']))  { $where[] = 'v.marque_id = ?';    $params[] = (int)$f['marque_id'];    $types .= 'i'; }
        if (!empty($f['modele_id']))  { $where[] = 'v.modele_id = ?';    $params[] = (int)$f['modele_id'];    $types .= 'i'; }
        if (!empty($f['carburant']))  { $where[] = 'v.carburant = ?';    $params[] = $f['carburant'];         $types .= 's'; }
        if (!empty($f['prix_max']))   { $where[] = 'v.prix <= ?';        $params[] = (float)$f['prix_max'];   $types .= 'd'; }
        if (!empty($f['prix_min']))   { $where[] = 'v.prix >= ?';        $params[] = (float)$f['prix_min'];   $types .= 'd'; }
        if (!empty($f['km_max']))     { $where[] = 'v.kilometrage <= ?'; $params[] = (int)$f['km_max'];       $types .= 'i'; }
        if (!empty($f['annee_min']))  { $where[] = 'v.annee >= ?';       $params[] = (int)$f['annee_min'];    $types .= 'i'; }
        if (!empty($f['search'])) {
            $where[] = '(ma.nom LIKE ? OR v.modele LIKE ? OR v.description LIKE ?)';
            $s        = '%' . $f['search'] . '%';
            $params[] = $s; $params[] = $s; $params[] = $s;
            $types   .= 'sss';
        }

        $w      = implode(' AND ', $where);
        $offset = ($page - 1) * $perPage;

        // Compte total
        $countSql = "SELECT COUNT(*) AS c
                     FROM   voitures v
                     JOIN   marques  ma ON ma.id = v.marque_id
                     LEFT JOIN modeles mo ON mo.id = v.modele_id
                     WHERE  $w";
        $stmt = $db->prepare($countSql);
        if ($types) $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $total = (int)$stmt->get_result()->fetch_assoc()['c'];
        $stmt->close();

        // Données paginées
        $stmt = $db->prepare(self::$select . " WHERE $w ORDER BY v.est_vedette DESC, v.created_at DESC LIMIT ? OFFSET ?");
        $stmt->bind_param($types . 'ii', ...array_merge($params, [$perPage, $offset]));
        $stmt->execute();
        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return ['data' => $rows, 'total' => $total, 'pages' => (int)ceil($total / $perPage)];
    }

    public static function getAllAdmin(): array
    {
        $db  = Database::getConnection();
        $res = $db->query(self::$select . " ORDER BY v.created_at DESC");
        return $res->fetch_all(MYSQLI_ASSOC);
    }

    public static function getById(int $id): ?array
    {
        $db   = Database::getConnection();
        $stmt = $db->prepare(self::$select . " WHERE v.id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $row  = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $row ?: null;
    }

    public static function getBySlug(string $slug): ?array
    {
        $db   = Database::getConnection();
        $stmt = $db->prepare(self::$select . " WHERE v.slug = ?");
        $stmt->bind_param('s', $slug);
        $stmt->execute();
        $row  = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $row ?: null;
    }

    public static function getVedettes(int $limit = 3): array
    {
        $db   = Database::getConnection();
        $stmt = $db->prepare(
            self::$select . " WHERE v.est_vedette = 1 AND v.statut = 'disponible'
            ORDER BY v.created_at DESC LIMIT ?"
        );
        $stmt->bind_param('i', $limit);
        $stmt->execute();
        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $rows;
    }

    public static function getMarques(): array
    {
        $res = Database::getConnection()->query(
            "SELECT id, nom FROM marques WHERE actif = 1 ORDER BY nom ASC"
        );
        return $res->fetch_all(MYSQLI_ASSOC);
    }

    public static function countDisponibles(): int
    {
        $row = Database::getConnection()
            ->query("SELECT COUNT(*) AS c FROM voitures WHERE statut = 'disponible'")
            ->fetch_assoc();
        return (int)$row['c'];
    }

    // ── Écriture ─────────────────────────────────────────────────────────────

    public static function create(array $d): int
    {
        $db   = Database::getConnection();
        $stmt = $db->prepare(
            'INSERT INTO voitures
                (marque_id, modele_id, modele, annee, prix, kilometrage,
                 carburant, transmission, puissance, couleur,
                 description, statut, est_vedette, image_principale, slug)
             VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)'
        );
        // i  i  s      i     d    i          s         s             i         s       s            s       i           s                s
        // mi mo modele annee prix kilometrage carburant transmission puissance couleur description statut est_vedette image_principale slug
        $stmt->bind_param('iisiidssisssiss',
            $d['marque_id'],
            $d['modele_id'],
            $d['modele'],
            $d['annee'],
            $d['prix'],
            $d['kilometrage'],
            $d['carburant'],
            $d['transmission'],
            $d['puissance'],
            $d['couleur'],
            $d['description'],
            $d['statut'],
            $d['est_vedette'],
            $d['image_principale'],
            $d['slug']
        );
        $stmt->execute();
        $id = (int)$db->insert_id;
        $stmt->close();
        return $id;
    }

    public static function update(int $id, array $d): bool
    {
        $db   = Database::getConnection();
        $stmt = $db->prepare(
            'UPDATE voitures
             SET marque_id=?, modele_id=?, modele=?, annee=?, prix=?, kilometrage=?,
                 carburant=?, transmission=?, puissance=?, couleur=?,
                 description=?, statut=?, est_vedette=?, image_principale=?, slug=?
             WHERE id=?'
        );
        // i  i  s      i     d    i          s         s             i         s       s            s       i           s                s    i
        $stmt->bind_param('iisiidssisssissi',
            $d['marque_id'],
            $d['modele_id'],
            $d['modele'],
            $d['annee'],
            $d['prix'],
            $d['kilometrage'],
            $d['carburant'],
            $d['transmission'],
            $d['puissance'],
            $d['couleur'],
            $d['description'],
            $d['statut'],
            $d['est_vedette'],
            $d['image_principale'],
            $d['slug'],
            $id
        );
        $stmt->execute();
        $ok = $stmt->affected_rows >= 0;
        $stmt->close();
        return $ok;
    }

    public static function delete(int $id): bool
    {
        $db   = Database::getConnection();
        $stmt = $db->prepare('DELETE FROM voitures WHERE id = ?');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $ok   = $stmt->affected_rows > 0;
        $stmt->close();
        return $ok;
    }

    public static function setStatut(int $id, string $statut): void
    {
        $db   = Database::getConnection();
        $stmt = $db->prepare('UPDATE voitures SET statut = ? WHERE id = ?');
        $stmt->bind_param('si', $statut, $id);
        $stmt->execute();
        $stmt->close();
    }
}