-- Active: 1765832433223@@localhost@3306@gardenjardin
CREATE DATABASE GardenJardin;
USE  GardenJardin;
CREATE TABLE  users (
    id int AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(30) NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    statut ENUM('waiting', 'block', 'improve') NOT NULL,
    role VARCHAR(40) NOT NULL
    
);
CREATE TABLE theme(
    id int AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(25) NOT NULL,
    badgeCouleur VARCHAR(30) NOT NULL,
    tags VARCHAR(30),
    id_user INT NOT NULL,
    FOREIGN KEY (id_user)REFERENCES users(id)
); 
CREATE TABLE note (
    id int AUTO_INCREMENT PRIMARY KEY,
    titre VARCHAR(50) NOT NULL,
    importance INT NOT NULL CHECK(importance BETWEEN 1 AND 5),
    contenu VARCHAR(255) NOT NULL,
    dateCreation DATE NOT NULL,
    id_theme INT NOT NULL ,
    FOREIGN KEY(id_theme) REFERENCES theme(id)
);
CREATE TABLE Signalement(
    id int AUTO_INCREMENT PRIMARY KEY,
    type VARCHAR(50) NOT NULL,
    raison VARCHAR(50) NOT NULL,
    statut ENUM('waiting', 'accepted', 'refused') NOT NULL
);

ALTER TABLE users
ADD confirm_password VARCHAR(255) AFTER password ;

CREATE TABLE garden(
    id INT AUTO_INCREMENT PRIMARY KEY
);
CREATE TABLE admin(
    id INT AUTO_INCREMENT PRIMARY KEY
);
CREATE TABLE role (
    id INT AUTO_INCREMENT PRIMARY KEY,
    statu ENUM('Admin', 'Garden') NOT NULL
);

ALTER TABLE users
ADD dateInscription DATETIME DEFAULT CURRENT_TIMESTAMP;

ALTER TABLE note ADD COLUMN etat  ENUM('active', 'archived') NOT NULL ;
ALTER TABLE note ADD COLUMN favori VARCHAR(25) NOT NULL ;

DESCRIBE Signalement;
ALTER TABLE signalement
ADD COLUMN id_note INT NULL,
ADD FOREIGN KEY (id_note) REFERENCES note(id) ON DELETE SET NULL,
ADD COLUMN id_user INT NULL,
ADD FOREIGN KEY (id_user) REFERENCES users(id) ON DELETE SET NULL,
ADD COLUMN id_user_reported INT NULL,
ADD FOREIGN KEY (id_user_reported) REFERENCES users(id) ON DELETE SET NULL

USE GardenJardin;

-- Insérer des données avec répartition égale des 3 statuts
INSERT INTO Signalement (type, raison, statut, id_note, id_user, id_user_reported) VALUES
-- Statut: 'waiting' (en attente)
('note', 'Contenu violent', 'waiting', 1, 2, NULL),
('user', 'Harcèlement', 'waiting', NULL, 3, 4),
('note', 'Spam commercial', 'waiting', 2, 5, NULL),
('user', 'Comportement abusif', 'waiting', NULL, 6, 7)
