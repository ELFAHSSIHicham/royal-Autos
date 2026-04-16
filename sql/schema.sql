-- Royal Autos — Schéma de base de données
-- MySQL 8 / MariaDB 10.6+

CREATE DATABASE IF NOT EXISTS royal_autos
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE royal_autos;

--VOITURES
CREATE TABLE IF NOT EXISTS voitures (
    id               INT UNSIGNED     AUTO_INCREMENT PRIMARY KEY,
    marque           VARCHAR(80)      NOT NULL,
    modele           VARCHAR(120)     NOT NULL,
    annee            YEAR             NOT NULL,
    prix             DECIMAL(10,2)    NOT NULL,
    kilometrage      INT UNSIGNED     NOT NULL DEFAULT 0,
    carburant        ENUM('Essence','Diesel','Hybride','Électrique','GPL') NOT NULL,
    transmission     ENUM('Manuelle','Automatique') NOT NULL DEFAULT 'Manuelle',
    puissance        SMALLINT UNSIGNED NULL     COMMENT 'Puissance en chevaux',
    couleur          VARCHAR(60)      NULL,
    portes           TINYINT UNSIGNED NOT NULL DEFAULT 5,
    places           TINYINT UNSIGNED NOT NULL DEFAULT 5,
    description      TEXT             NULL,
    options          JSON             NULL      COMMENT 'Liste des options (JSON array)',
    statut           ENUM('disponible','reserve','vendu','maintenance') NOT NULL DEFAULT 'disponible',
    est_vedette      TINYINT(1)       NOT NULL DEFAULT 0,
    image_principale VARCHAR(255)     NULL,
    images           JSON             NULL      COMMENT 'Photos supplémentaires (JSON array)',
    slug             VARCHAR(220)     NOT NULL UNIQUE,
    created_at       TIMESTAMP        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at       TIMESTAMP        NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_marque  (marque),
    INDEX idx_statut  (statut),
    INDEX idx_prix    (prix),
    INDEX idx_annee   (annee),
    INDEX idx_vedette (est_vedette)
) ENGINE=InnoDB;

--CLIENTS
CREATE TABLE IF NOT EXISTS clients (
    id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nom        VARCHAR(100) NOT NULL,
    prenom     VARCHAR(100) NOT NULL,
    email      VARCHAR(180) NOT NULL UNIQUE,
    telephone  VARCHAR(20)  NULL,
    ville      VARCHAR(100) NULL,
    created_at TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_email (email)
) ENGINE=InnoDB;

--RÉSERVATIONS
CREATE TABLE IF NOT EXISTS reservations (
    id                INT UNSIGNED  AUTO_INCREMENT PRIMARY KEY,
    reference         VARCHAR(20)   NOT NULL UNIQUE COMMENT 'Ex: RA-2026-001234',
    voiture_id        INT UNSIGNED  NOT NULL,
    client_id         INT UNSIGNED  NOT NULL,
    montant           DECIMAL(10,2) NOT NULL COMMENT 'Prix total du véhicule',
    acompte           DECIMAL(10,2) NOT NULL DEFAULT 0 COMMENT 'Acompte réglé via Stripe',
    statut            ENUM('en_attente','confirmee','payee','annulee','terminee') NOT NULL DEFAULT 'en_attente',
    stripe_session_id VARCHAR(255)  NULL,
    stripe_payment_id VARCHAR(255)  NULL,
    date_rdv          DATETIME      NULL COMMENT 'Rendez-vous souhaité',
    notes             TEXT          NULL,
    created_at        TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at        TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY fk_resa_voiture (voiture_id) REFERENCES voitures(id) ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY fk_resa_client  (client_id)  REFERENCES clients(id)  ON DELETE RESTRICT ON UPDATE CASCADE,
    INDEX idx_statut    (statut),
    INDEX idx_reference (reference),
    INDEX idx_stripe    (stripe_session_id)
) ENGINE=InnoDB;

--MESSAGES CONTACT
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

--ADMINS
CREATE TABLE IF NOT EXISTS admins (
    id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nom        VARCHAR(100) NOT NULL,
    email      VARCHAR(180) NOT NULL UNIQUE,
    password   VARCHAR(255) NOT NULL COMMENT 'bcrypt — jamais en clair',
    role       ENUM('admin','superadmin') NOT NULL DEFAULT 'admin',
    last_login DATETIME     NULL,
    created_at TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

--ADMIN PAR DÉFAUT
-- Mot de passe : Admin@RoyalAutos2026 (à changer immédiatement en prod)
INSERT INTO admins (nom, email, password, role)
VALUES ('Administrateur', 'admin@royalautos.fr',
        '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uFpBHnRgm', 'superadmin')
ON DUPLICATE KEY UPDATE id = id;
