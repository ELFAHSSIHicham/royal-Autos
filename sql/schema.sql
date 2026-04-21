-- Royal Autos — Schéma complet

CREATE DATABASE IF NOT EXISTS royal_autos
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;
USE royal_autos;

-- MARQUES
CREATE TABLE IF NOT EXISTS marques (
                                       id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                                       nom        VARCHAR(80)  NOT NULL UNIQUE,
    logo_url   VARCHAR(255) NULL,
    actif      TINYINT(1)   NOT NULL DEFAULT 1,
    created_at TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB;

INSERT INTO marques (nom) VALUES
                              ('Renault'),('Peugeot'),('Dacia'),('Citroën'),
                              ('Volkswagen'),('Toyota'),('BMW'),('Mercedes-Benz'),
                              ('Audi'),('Ford'),('Opel'),('Hyundai'),('Kia'),
                              ('Nissan'),('Fiat'),('Skoda'),('Seat'),
                              ('Volvo'),('Tesla'),('Mazda')
    ON DUPLICATE KEY UPDATE id = id;

-- MODÈLES (liés aux marques — IDs résolus par nom, jamais hardcodés)
CREATE TABLE IF NOT EXISTS modeles (
                                       id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                                       marque_id  INT UNSIGNED NOT NULL,
                                       nom        VARCHAR(120) NOT NULL,
    actif      TINYINT(1)   NOT NULL DEFAULT 1,
    created_at TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY fk_modele_marque (marque_id) REFERENCES marques(id) ON DELETE CASCADE,
    UNIQUE KEY uq_marque_modele (marque_id, nom),
    INDEX idx_marque (marque_id)
    ) ENGINE=InnoDB;

-- ── Renault
INSERT INTO modeles (marque_id, nom)
SELECT id, m.nom FROM marques, (
    SELECT 'Clio'      AS nom UNION SELECT 'Mégane'   UNION SELECT 'Captur'
    UNION SELECT 'Kadjar'    UNION SELECT 'Austral'   UNION SELECT 'Arkana'
    UNION SELECT 'Zoe'       UNION SELECT 'Twingo'    UNION SELECT 'Scenic'
    UNION SELECT 'Talisman'  UNION SELECT 'Koleos'
) m WHERE marques.nom = 'Renault'
    ON DUPLICATE KEY UPDATE modeles.id = modeles.id;

-- ── Peugeot
INSERT INTO modeles (marque_id, nom)
SELECT id, m.nom FROM marques, (
    SELECT '208'   AS nom UNION SELECT '308'     UNION SELECT '408'
    UNION SELECT '508'   UNION SELECT '2008'     UNION SELECT '3008'
    UNION SELECT '5008'  UNION SELECT 'Rifter'   UNION SELECT 'Traveller'
) m WHERE marques.nom = 'Peugeot'
    ON DUPLICATE KEY UPDATE modeles.id = modeles.id;

-- ── Dacia
INSERT INTO modeles (marque_id, nom)
SELECT id, m.nom FROM marques, (
    SELECT 'Sandero' AS nom UNION SELECT 'Logan'   UNION SELECT 'Duster'
    UNION SELECT 'Jogger'   UNION SELECT 'Spring'  UNION SELECT 'Bigster'
    UNION SELECT 'Dokker'
) m WHERE marques.nom = 'Dacia'
    ON DUPLICATE KEY UPDATE modeles.id = modeles.id;

-- ── Citroën
INSERT INTO modeles (marque_id, nom)
SELECT id, m.nom FROM marques, (
    SELECT 'C1'          AS nom UNION SELECT 'C3'          UNION SELECT 'C4'
    UNION SELECT 'C5 X'        UNION SELECT 'C3 Aircross'  UNION SELECT 'C5 Aircross'
    UNION SELECT 'Berlingo'    UNION SELECT 'ë-C3'         UNION SELECT 'C-Elysée'
) m WHERE marques.nom = 'Citroën'
    ON DUPLICATE KEY UPDATE modeles.id = modeles.id;

-- ── Volkswagen
INSERT INTO modeles (marque_id, nom)
SELECT id, m.nom FROM marques, (
    SELECT 'Golf'    AS nom UNION SELECT 'Polo'    UNION SELECT 'Passat'
    UNION SELECT 'Tiguan'   UNION SELECT 'Touareg' UNION SELECT 'T-Roc'
    UNION SELECT 'T-Cross'  UNION SELECT 'ID.3'    UNION SELECT 'ID.4'
    UNION SELECT 'ID.7'     UNION SELECT 'Arteon'  UNION SELECT 'Touran'
) m WHERE marques.nom = 'Volkswagen'
    ON DUPLICATE KEY UPDATE modeles.id = modeles.id;

-- ── Toyota
INSERT INTO modeles (marque_id, nom)
SELECT id, m.nom FROM marques, (
    SELECT 'Yaris'        AS nom UNION SELECT 'Corolla'     UNION SELECT 'C-HR'
    UNION SELECT 'RAV4'         UNION SELECT 'Highlander'   UNION SELECT 'Land Cruiser'
    UNION SELECT 'Aygo X'       UNION SELECT 'Prius'        UNION SELECT 'bZ4X'
    UNION SELECT 'GR86'         UNION SELECT 'Supra'
) m WHERE marques.nom = 'Toyota'
    ON DUPLICATE KEY UPDATE modeles.id = modeles.id;

-- ── BMW
INSERT INTO modeles (marque_id, nom)
SELECT id, m.nom FROM marques, (
    SELECT 'Série 1' AS nom UNION SELECT 'Série 2' UNION SELECT 'Série 3'
    UNION SELECT 'Série 4'  UNION SELECT 'Série 5' UNION SELECT 'Série 7'
    UNION SELECT 'Série 8'  UNION SELECT 'X1'      UNION SELECT 'X2'
    UNION SELECT 'X3'       UNION SELECT 'X5'      UNION SELECT 'X6'
    UNION SELECT 'X7'       UNION SELECT 'iX'      UNION SELECT 'i4'
    UNION SELECT 'i7'       UNION SELECT 'M2'      UNION SELECT 'M3'
    UNION SELECT 'M4'       UNION SELECT 'M5'
) m WHERE marques.nom = 'BMW'
    ON DUPLICATE KEY UPDATE modeles.id = modeles.id;

-- ── Mercedes-Benz
INSERT INTO modeles (marque_id, nom)
SELECT id, m.nom FROM marques, (
    SELECT 'Classe A' AS nom UNION SELECT 'Classe B' UNION SELECT 'Classe C'
    UNION SELECT 'Classe E'  UNION SELECT 'Classe S' UNION SELECT 'Classe G'
    UNION SELECT 'GLA'       UNION SELECT 'GLB'      UNION SELECT 'GLC'
    UNION SELECT 'GLE'       UNION SELECT 'GLS'      UNION SELECT 'AMG GT'
    UNION SELECT 'EQA'       UNION SELECT 'EQB'      UNION SELECT 'EQC'
    UNION SELECT 'EQE'       UNION SELECT 'EQS'      UNION SELECT 'CLA'
    UNION SELECT 'CLS'
) m WHERE marques.nom = 'Mercedes-Benz'
    ON DUPLICATE KEY UPDATE modeles.id = modeles.id;

-- ── Audi
INSERT INTO modeles (marque_id, nom)
SELECT id, m.nom FROM marques, (
    SELECT 'A1'      AS nom UNION SELECT 'A3'       UNION SELECT 'A4'
    UNION SELECT 'A5'       UNION SELECT 'A6'       UNION SELECT 'A7'
    UNION SELECT 'A8'       UNION SELECT 'Q2'       UNION SELECT 'Q3'
    UNION SELECT 'Q5'       UNION SELECT 'Q7'       UNION SELECT 'Q8'
    UNION SELECT 'e-tron'   UNION SELECT 'e-tron GT' UNION SELECT 'TT'
    UNION SELECT 'R8'       UNION SELECT 'RS3'      UNION SELECT 'RS6'
    UNION SELECT 'RS7'
) m WHERE marques.nom = 'Audi'
    ON DUPLICATE KEY UPDATE modeles.id = modeles.id;

-- ── Ford
INSERT INTO modeles (marque_id, nom)
SELECT id, m.nom FROM marques, (
    SELECT 'Fiesta'         AS nom UNION SELECT 'Focus'   UNION SELECT 'Puma'
    UNION SELECT 'Kuga'            UNION SELECT 'Explorer' UNION SELECT 'Mustang'
    UNION SELECT 'Mustang Mach-E'  UNION SELECT 'Ranger'   UNION SELECT 'Transit'
    UNION SELECT 'Galaxy'
) m WHERE marques.nom = 'Ford'
    ON DUPLICATE KEY UPDATE modeles.id = modeles.id;

-- ── Opel
INSERT INTO modeles (marque_id, nom)
SELECT id, m.nom FROM marques, (
    SELECT 'Corsa'    AS nom UNION SELECT 'Astra'     UNION SELECT 'Mokka'
    UNION SELECT 'Crossland'  UNION SELECT 'Grandland' UNION SELECT 'Zafira'
    UNION SELECT 'Insignia'   UNION SELECT 'Combo'
) m WHERE marques.nom = 'Opel'
    ON DUPLICATE KEY UPDATE modeles.id = modeles.id;

-- ── Hyundai
INSERT INTO modeles (marque_id, nom)
SELECT id, m.nom FROM marques, (
    SELECT 'i10'      AS nom UNION SELECT 'i20'      UNION SELECT 'i30'
    UNION SELECT 'IONIQ 5'   UNION SELECT 'IONIQ 6'  UNION SELECT 'Tucson'
    UNION SELECT 'Santa Fe'  UNION SELECT 'Kona'     UNION SELECT 'Bayon'
) m WHERE marques.nom = 'Hyundai'
    ON DUPLICATE KEY UPDATE modeles.id = modeles.id;

-- ── Kia
INSERT INTO modeles (marque_id, nom)
SELECT id, m.nom FROM marques, (
    SELECT 'Picanto'  AS nom UNION SELECT 'Rio'       UNION SELECT 'Stonic'
    UNION SELECT 'Ceed'      UNION SELECT 'XCeed'     UNION SELECT 'Sportage'
    UNION SELECT 'Sorento'   UNION SELECT 'Niro'      UNION SELECT 'EV6'
    UNION SELECT 'EV9'
) m WHERE marques.nom = 'Kia'
    ON DUPLICATE KEY UPDATE modeles.id = modeles.id;

-- ── Nissan
INSERT INTO modeles (marque_id, nom)
SELECT id, m.nom FROM marques, (
    SELECT 'Micra'    AS nom UNION SELECT 'Juke'      UNION SELECT 'Qashqai'
    UNION SELECT 'X-Trail'   UNION SELECT 'Leaf'      UNION SELECT 'Ariya'
    UNION SELECT 'Navara'    UNION SELECT 'Pathfinder'
) m WHERE marques.nom = 'Nissan'
    ON DUPLICATE KEY UPDATE modeles.id = modeles.id;

-- ── Fiat
INSERT INTO modeles (marque_id, nom)
SELECT id, m.nom FROM marques, (
    SELECT '500'      AS nom UNION SELECT '500X'      UNION SELECT '500e'
    UNION SELECT 'Tipo'      UNION SELECT 'Panda'     UNION SELECT 'Doblo'
    UNION SELECT 'Ducato'
) m WHERE marques.nom = 'Fiat'
    ON DUPLICATE KEY UPDATE modeles.id = modeles.id;

-- ── Skoda
INSERT INTO modeles (marque_id, nom)
SELECT id, m.nom FROM marques, (
    SELECT 'Fabia'    AS nom UNION SELECT 'Scala'     UNION SELECT 'Octavia'
    UNION SELECT 'Superb'    UNION SELECT 'Kamiq'     UNION SELECT 'Karoq'
    UNION SELECT 'Kodiaq'    UNION SELECT 'Enyaq'
) m WHERE marques.nom = 'Skoda'
    ON DUPLICATE KEY UPDATE modeles.id = modeles.id;

-- ── Seat
INSERT INTO modeles (marque_id, nom)
SELECT id, m.nom FROM marques, (
    SELECT 'Ibiza'    AS nom UNION SELECT 'Leon'      UNION SELECT 'Arona'
    UNION SELECT 'Ateca'     UNION SELECT 'Tarraco'   UNION SELECT 'Alhambra'
    UNION SELECT 'Toledo'
) m WHERE marques.nom = 'Seat'
    ON DUPLICATE KEY UPDATE modeles.id = modeles.id;

-- ── Volvo
INSERT INTO modeles (marque_id, nom)
SELECT id, m.nom FROM marques, (
    SELECT 'S60'  AS nom UNION SELECT 'S90'  UNION SELECT 'V60'
    UNION SELECT 'V90'   UNION SELECT 'XC40' UNION SELECT 'XC60'
    UNION SELECT 'XC90'  UNION SELECT 'C40'  UNION SELECT 'EX30'
    UNION SELECT 'EX90'
) m WHERE marques.nom = 'Volvo'
    ON DUPLICATE KEY UPDATE modeles.id = modeles.id;

-- ── Tesla
INSERT INTO modeles (marque_id, nom)
SELECT id, m.nom FROM marques, (
    SELECT 'Model 3' AS nom UNION SELECT 'Model S'    UNION SELECT 'Model X'
    UNION SELECT 'Model Y'   UNION SELECT 'Cybertruck'
) m WHERE marques.nom = 'Tesla'
    ON DUPLICATE KEY UPDATE modeles.id = modeles.id;

-- ── Mazda
INSERT INTO modeles (marque_id, nom)
SELECT id, m.nom FROM marques, (
    SELECT 'Mazda2' AS nom UNION SELECT 'Mazda3' UNION SELECT 'Mazda6'
    UNION SELECT 'CX-3'    UNION SELECT 'CX-30'  UNION SELECT 'CX-5'
    UNION SELECT 'CX-60'   UNION SELECT 'MX-5'   UNION SELECT 'MX-30'
) m WHERE marques.nom = 'Mazda'
    ON DUPLICATE KEY UPDATE modeles.id = modeles.id;

-- VOITURES
CREATE TABLE IF NOT EXISTS voitures (
                                        id               INT UNSIGNED  AUTO_INCREMENT PRIMARY KEY,
                                        marque_id        INT UNSIGNED  NOT NULL,
                                        modele_id        INT UNSIGNED  NULL,
                                        modele           VARCHAR(120)  NOT NULL,
    annee            YEAR          NOT NULL,
    prix             DECIMAL(10,2) NOT NULL,
    kilometrage      INT UNSIGNED  NOT NULL DEFAULT 0,
    carburant        ENUM('Essence','Diesel','Hybride','Électrique','GPL') NOT NULL DEFAULT 'Essence',
    transmission     ENUM('Manuelle','Automatique')                        NOT NULL DEFAULT 'Manuelle',
    statut           ENUM('disponible','reserve','vendu','maintenance')    NOT NULL DEFAULT 'disponible',
    puissance        SMALLINT UNSIGNED NULL,
    couleur          VARCHAR(60)   NULL,
    description      TEXT          NULL,
    est_vedette      TINYINT(1)    NOT NULL DEFAULT 0,
    image_principale VARCHAR(255)  NULL,
    slug             VARCHAR(220)  NOT NULL UNIQUE,
    created_at       TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at       TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY fk_voiture_marque  (marque_id) REFERENCES marques(id) ON DELETE RESTRICT,
    FOREIGN KEY fk_voiture_modele  (modele_id) REFERENCES modeles(id) ON DELETE SET NULL,
    INDEX idx_marque  (marque_id),
    INDEX idx_modele  (modele_id),
    INDEX idx_statut  (statut),
    INDEX idx_prix    (prix),
    INDEX idx_annee   (annee),
    INDEX idx_vedette (est_vedette)
    ) ENGINE=InnoDB;

-- IMAGES VOITURES (galerie page détail)
CREATE TABLE IF NOT EXISTS voiture_images (
                                              id         INT UNSIGNED     AUTO_INCREMENT PRIMARY KEY,
                                              voiture_id INT UNSIGNED     NOT NULL,
                                              url        VARCHAR(255)     NOT NULL,
    ordre      TINYINT UNSIGNED NOT NULL DEFAULT 1,
    created_at TIMESTAMP        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY fk_img_voiture (voiture_id) REFERENCES voitures(id) ON DELETE CASCADE,
    INDEX idx_voiture (voiture_id)
    ) ENGINE=InnoDB;

-- CLIENTS
CREATE TABLE IF NOT EXISTS clients (
                                       id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                                       nom        VARCHAR(100) NOT NULL,
    prenom     VARCHAR(100) NOT NULL,
    email      VARCHAR(180) NOT NULL UNIQUE,
    telephone  VARCHAR(20)  NULL,
    created_at TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_email (email)
    ) ENGINE=InnoDB;

-- RÉSERVATIONS
CREATE TABLE IF NOT EXISTS reservations (
                                            id                INT UNSIGNED  AUTO_INCREMENT PRIMARY KEY,
                                            reference         VARCHAR(20)   NOT NULL UNIQUE,
    voiture_id        INT UNSIGNED  NOT NULL,
    client_id         INT UNSIGNED  NOT NULL,
    montant           DECIMAL(10,2) NOT NULL,
    statut            ENUM('en_attente','confirmee','payee','annulee','terminee') NOT NULL DEFAULT 'en_attente',
    stripe_session_id VARCHAR(255)  NULL,
    stripe_payment_id VARCHAR(255)  NULL,
    notes             TEXT          NULL,
    created_at        TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at        TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY fk_resa_voiture (voiture_id) REFERENCES voitures(id) ON DELETE RESTRICT,
    FOREIGN KEY fk_resa_client  (client_id)  REFERENCES clients(id)  ON DELETE RESTRICT,
    INDEX idx_statut (statut),
    INDEX idx_stripe (stripe_session_id)
    ) ENGINE=InnoDB;

-- MESSAGES CONTACT
CREATE TABLE IF NOT EXISTS messages_contact (
                                                id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                                                nom        VARCHAR(200) NOT NULL,
    email      VARCHAR(180) NOT NULL,
    telephone  VARCHAR(20)  NULL,
    sujet      VARCHAR(200) NULL,
    message    TEXT         NOT NULL,
    lu         TINYINT(1)   NOT NULL DEFAULT 0,
    created_at TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_lu (lu)
    ) ENGINE=InnoDB;

-- ADMINS
CREATE TABLE IF NOT EXISTS admins (
                                      id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                                      nom        VARCHAR(100) NOT NULL,
    email      VARCHAR(180) NOT NULL UNIQUE,
    password   VARCHAR(255) NOT NULL,
    role       ENUM('admin','superadmin') NOT NULL DEFAULT 'admin',
    last_login DATETIME     NULL,
    created_at TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB;

-- Admin par défaut (mot de passe : Admin@RoyalAutos2026)
INSERT INTO admins (nom, email, password, role) VALUES
    ('Administrateur', 'admin@royalautos.fr',
     '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uFpBHnRgm', 'superadmin')
    ON DUPLICATE KEY UPDATE id = id;