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







