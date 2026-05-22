<?php
namespace Models\Voiture;

use Models\Database;

class Voiture
{
    private static string $select = "
        SELECT  v.*,
                ma.nom        AS marque,
                mo.nom        AS nom_modele
        FROM    voitures v
        JOIN    marques  ma ON ma.id = v.marque_id
        LEFT JOIN modeles mo ON mo.id = v.modele_id
    ";

    // ── Lecture ───────────────────────────────────────────────────────────────

    public static function getAll(array $f = [], int $page = 1, int $perPage = 9): array
    {
        $db     = Database::getConnection();
        $where  = ["v.statut = 'disponible'"];
        $params = [];
        $types  = '';

        if (!empty($f['marque_id']))     { $where[] = 'v.marque_id = ?';     $params[] = (int)$f['marque_id'];    $types .= 'i'; }
        if (!empty($f['modele_id']))     { $where[] = 'v.modele_id = ?';     $params[] = (int)$f['modele_id'];    $types .= 'i'; }
        if (!empty($f['carburant']))     { $where[] = 'v.carburant = ?';     $params[] = $f['carburant'];         $types .= 's'; }
        if (!empty($f['transmission']))  { $where[] = 'v.transmission = ?';  $params[] = $f['transmission'];      $types .= 's'; }
        if (!empty($f['prix_max']))      { $where[] = 'v.prix <= ?';         $params[] = (float)$f['prix_max'];   $types .= 'd'; }
        if (!empty($f['prix_min']))      { $where[] = 'v.prix >= ?';         $params[] = (float)$f['prix_min'];   $types .= 'd'; }
        if (!empty($f['km_max']))        { $where[] = 'v.kilometrage <= ?';  $params[] = (int)$f['km_max'];       $types .= 'i'; }
        if (!empty($f['annee_min']))     { $where[] = 'v.annee >= ?';        $params[] = (int)$f['annee_min'];    $types .= 'i'; }
        if (!empty($f['annee_max']))     { $where[] = 'v.annee <= ?';        $params[] = (int)$f['annee_max'];    $types .= 'i'; }
        if (!empty($f['search'])) {
            $where[]  = '(ma.nom LIKE ? OR v.modele LIKE ? OR v.description LIKE ?)';
            $s        = '%' . $f['search'] . '%';
            $params[] = $s; $params[] = $s; $params[] = $s;
            $types   .= 'sss';
        }

        $w      = implode(' AND ', $where);
        $offset = ($page - 1) * $perPage;

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

    public static function getMarquesAvecVoitures(): array
    {
        $res = Database::getConnection()->query(
            "SELECT DISTINCT ma.id, ma.nom
             FROM marques ma
             INNER JOIN voitures v ON v.marque_id = ma.id AND v.statut = 'disponible'
             ORDER BY ma.nom ASC"
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

    // ── Images ────────────────────────────────────────────────────────────────

    public static function getImages(int $voitureId): array
    {
        $db   = Database::getConnection();
        $stmt = $db->prepare(
            'SELECT id, url, ordre FROM voiture_images WHERE voiture_id = ? ORDER BY ordre ASC'
        );
        $stmt->bind_param('i', $voitureId);
        $stmt->execute();
        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $rows;
    }

    public static function addImage(int $voitureId, string $url, int $ordre = 1): void
    {
        $db   = Database::getConnection();
        $stmt = $db->prepare(
            'INSERT INTO voiture_images (voiture_id, url, ordre) VALUES (?, ?, ?)'
        );
        $stmt->bind_param('isi', $voitureId, $url, $ordre);
        $stmt->execute();
        $stmt->close();
    }

    public static function deleteImage(int $imageId): void
    {
        $db   = Database::getConnection();
        $stmt = $db->prepare('DELETE FROM voiture_images WHERE id = ?');
        $stmt->bind_param('i', $imageId);
        $stmt->execute();
        $stmt->close();
    }

    public static function deleteAllImages(int $voitureId): void
    {
        $db   = Database::getConnection();
        $stmt = $db->prepare('DELETE FROM voiture_images WHERE voiture_id = ?');
        $stmt->bind_param('i', $voitureId);
        $stmt->execute();
        $stmt->close();
    }

    public static function saveNewImages(int $voitureId, array $files, int $ordreDepart = 1): ?string
    {
        $premiereUrl = null;
        $ordre       = $ordreDepart;
        $allow       = ['jpg', 'jpeg', 'png', 'webp'];

        // Chemin absolu du dossier uploads
        $uploadDir = dirname(__DIR__, 3) . '/storage/uploads/';

        // Créer le dossier si absent
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0775, true);
        }

        // Vérifier que le dossier est accessible en écriture
        if (!is_writable($uploadDir)) {
            error_log('[RoyalAutos] storage/uploads non accessible en écriture : ' . $uploadDir);
            return null;
        }

        foreach ($files as $file) {
            // Ignorer les slots vides (input multiple sans fichier)
            if (empty($file['tmp_name']) || $file['error'] !== UPLOAD_ERR_OK) {
                if (!empty($file['error']) && $file['error'] !== UPLOAD_ERR_NO_FILE) {
                    error_log('[RoyalAutos] Erreur upload code ' . $file['error'] . ' fichier ' . ($file['name'] ?? ''));
                }
                continue;
            }

            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            if (!in_array($ext, $allow, true)) {
                error_log('[RoyalAutos] Extension refusée : ' . $ext);
                continue;
            }
            if ($file['size'] > 5 * 1024 * 1024) {
                error_log('[RoyalAutos] Fichier trop lourd : ' . $file['size']);
                continue;
            }

            $name = uniqid('car_', true) . '.' . $ext;
            $dest = $uploadDir . $name;

            if (!move_uploaded_file($file['tmp_name'], $dest)) {
                error_log('[RoyalAutos] move_uploaded_file échoué : ' . $file['tmp_name'] . ' → ' . $dest);
                continue;
            }

            $url = '/uploads/' . $name;
            self::addImage($voitureId, $url, $ordre++);
            if ($premiereUrl === null) $premiereUrl = $url;
        }

        return $premiereUrl;
    }

    // ── Écriture ──────────────────────────────────────────────────────────────

    public static function create(array $d): int
    {
        $db   = Database::getConnection();
        $stmt = $db->prepare(
            'INSERT INTO voitures
                (marque_id, modele_id, modele, annee, prix, kilometrage,
                 carburant, transmission, puissance, couleur, motorisation, finition,
                 portes, places, date_mise_circulation, date_immatriculation,
                 description, statut, est_vedette, image_principale, slug)
             VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)'
        );
        $stmt->bind_param('iisiidssissssiissssiss',
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
            $d['motorisation'],
            $d['finition'],
            $d['portes'],
            $d['places'],
            $d['date_mise_circulation'],
            $d['date_immatriculation'],
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
                 carburant=?, transmission=?, puissance=?, couleur=?, motorisation=?, finition=?,
                 portes=?, places=?, date_mise_circulation=?, date_immatriculation=?,
                 description=?, statut=?, est_vedette=?, image_principale=?, slug=?
             WHERE id=?'
        );
        $stmt->bind_param('iisiidssissssiisssissi',
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
            $d['motorisation'],
            $d['finition'],
            $d['portes'],
            $d['places'],
            $d['date_mise_circulation'],
            $d['date_immatriculation'],
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

    public static function createMarque(string $nom): int
    {
        $db   = Database::getConnection();
        $stmt = $db->prepare('INSERT INTO marques (nom) VALUES (?)');
        $stmt->bind_param('s', $nom);
        $stmt->execute();
        $id = (int)$db->insert_id;
        $stmt->close();
        return $id;
    }

    public static function createModele(int $marqueId, string $nom): int
    {
        $db   = Database::getConnection();
        $stmt = $db->prepare('INSERT INTO modeles (marque_id, nom) VALUES (?,?)');
        $stmt->bind_param('is', $marqueId, $nom);
        $stmt->execute();
        $id = (int)$db->insert_id;
        $stmt->close();
        return $id;
    }
}