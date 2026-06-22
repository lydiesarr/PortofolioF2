-- Base de données : portfolio
-- À exécuter dans phpMyAdmin > onglet SQL

CREATE DATABASE IF NOT EXISTS portfolio CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE portfolio;

CREATE TABLE IF NOT EXISTS projets (
    id INT PRIMARY KEY AUTO_INCREMENT,
    titre VARCHAR(150) NOT NULL,
    description TEXT NOT NULL,
    technologies VARCHAR(255) NOT NULL,
    image VARCHAR(255) DEFAULT NULL,
    lien VARCHAR(255) DEFAULT NULL,
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS messages_contact (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL,
    message TEXT NOT NULL,
    lu TINYINT(1) DEFAULT 0,
    date_envoi DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS demandes_projet (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL,
    type_projet VARCHAR(100) NOT NULL,
    description TEXT NOT NULL,
    budget VARCHAR(50) DEFAULT NULL,
    lu TINYINT(1) DEFAULT 0,
    date_demande DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS administrateurs (
    id INT PRIMARY KEY AUTO_INCREMENT,
    prenom VARCHAR(100) NOT NULL,
    nom VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    mot_de_passe VARCHAR(255) NOT NULL,
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS visites (
    id INT PRIMARY KEY AUTO_INCREMENT,
    adresse_ip VARCHAR(45) NOT NULL,
    page VARCHAR(255) NOT NULL,
    date_visite DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Données de départ : projets
INSERT INTO projets (titre, description, technologies, lien) VALUES
('TechEdu', 'Projet de groupe pour la création d\'une école de formation technologique dédiée à l\'excellence académique et professionnelle en Afrique.', 'HTML, CSS', NULL),
('ESTM', 'Projet solo axé sur la maîtrise des balises HTML. Site de promotion de mon école pour lui donner plus de visibilité auprès des collèges et lycées.', 'HTML, CSS', NULL),
('Projet Fédérations', 'Projet entrepreneurial combinant administration réseau et développement pour résoudre des problématiques complexes de flux de données.', 'HTML, CSS, JavaScript, PHP', NULL),
('Site Soins Capillaires', 'Conçu de A à Z comme terrain d\'entraînement. Transformer une thématique esthétique en une interface fluide.', 'HTML, CSS, JavaScript', NULL),
('Site Gourmandise', 'Solution métier complète pour un commerce local. Focus sur l\'architecture d\'information et la fluidité de navigation.', 'HTML, CSS, JavaScript', NULL),
('Club des Jeunes Filles', 'Association qui défend les droits de la femme et lutte contre les violences faites aux femmes et aux filles.', 'Groupe, Association', NULL),
('Porte-parole', 'Élue porte-parole du gouvernement scolaire. Développement de la confiance en soi et de la prise de parole en public.', 'Groupe, École', NULL);

-- Compte super admin (Lydi Sarr) — id=2 protégé
-- Compte professeur (Cherif Diouf) — id=1
-- Les mots de passe sont à hacher via Setup_prof.php ou depuis l'interface admin
