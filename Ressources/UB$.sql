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



CREATE USER 'UBS_ADMIN'@'%' IDENTIFIED VIA mysql_native_password USING '***';GRANT ALL PRIVILEGES ON *.* TO 'UBS_ADMIN'@'%' REQUIRE NONE WITH GRANT OPTION MAX_QUERIES_PER_HOUR 0 MAX_CONNECTIONS_PER_HOUR 0 MAX_UPDATES_PER_HOUR 0 MAX_USER_CONNECTIONS 0;GRANT ALL PRIVILEGES ON `UB$`.* TO 'UBS_ADMIN'@'%';