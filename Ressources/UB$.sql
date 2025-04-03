-- Création de la base de données
CREATE DATABASE IF NOT EXISTS `UB$`;
USE `UB$`;

-- Création de la table des utilisateurs
CREATE TABLE user (
    idUser INT PRIMARY KEY AUTO_INCREMENT,
    email VARCHAR(255) NOT NULL,
    Firstname VARCHAR(255),
    Lastname VARCHAR(255),
    Token VARCHAR(500),
    Password VARCHAR(500) NOT NULL,
    currency VARCHAR(3)
);

-- Table pour les informations économiques des utilisateurs
CREATE TABLE economy (
    idEconomy INT PRIMARY KEY AUTO_INCREMENT,
    idUser INT,
    monthlyInput DOUBLE,
    monthlyLimit DOUBLE,
    spendAim DOUBLE,
    BaseMoney DOUBLE,
    FOREIGN KEY (idUser) REFERENCES user(idUser) ON DELETE CASCADE
);

-- Table des types de dépenses
CREATE TABLE spendTypes (
    idSpendingType INT PRIMARY KEY AUTO_INCREMENT,
    Type VARCHAR(100) NOT NULL
);

-- Table des dépenses
CREATE TABLE spending (
    idSpending INT PRIMARY KEY AUTO_INCREMENT,
    idEconomy INT,
    idSpendType INT,
    amount INT,
    FOREIGN KEY (idEconomy) REFERENCES economy(idEconomy) ON DELETE CASCADE,
    FOREIGN KEY (idSpendType) REFERENCES spendTypes(idSpendingType)
);
